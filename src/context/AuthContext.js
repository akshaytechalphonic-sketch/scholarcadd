import { createContext, useContext, useState, useEffect, useMemo } from "react";
import { API_BASE_URL, APIENDPOINTS } from "../../apiconfig";
const AuthContext = createContext();
import Cookies from "js-cookie";

export const AuthProvider = ({ children }) => {
  const [token, setToken] = useState(() => {
    if (typeof window !== "undefined") {
      return Cookies.get("access_token") || null;
    }
    return null;
  });
  const [loading, setLoading] = useState(true);
  const [categories, setCategories] = useState(() => {
    if (typeof window !== "undefined") {
      try {
        const saved = localStorage.getItem("categories");
        return saved ? JSON.parse(saved) : [];
      } catch (e) {
        return [];
      }
    }
    return [];
  });
   const [userLocation, setUserLocation] = useState(null);
    useEffect(() => {
      const saved = sessionStorage.getItem("userLocation");
      if (saved) {
        try {
          const parsed = JSON.parse(saved);
          setUserLocation(parsed);
        } catch (e) {
          console.error(" Failed to parse userLocation JSON", e);
        }
      }
    }, []);
  
  const active_country = useMemo(() => {
    return userLocation?.raw?.country.toLowerCase() || "in";
  }, [userLocation]);

  const [user, setUser] = useState(() => {
    if (typeof window !== "undefined") {
      try {
        const saved = localStorage.getItem("auth_user");
        return saved ? JSON.parse(saved) : null;
      } catch (e) {
        return null;
      }
    }
    return null;
  });

  const loginUser = async (email = "admin@hotelres.com", password = "12345678") => {
    try {
      setLoading(true);
      const res = await fetch(`${API_BASE_URL}${APIENDPOINTS.LOGIN}`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          email,
          password,
        }),
      }).catch((err) => {
        console.error("Login fetch error:", err);
        return null;
      });

      if (!res || !res.ok) {
        throw new Error(res ? `Login failed with status: ${res.status}` : "Network error: Unable to reach API server");
      }
      const contentType = res?.headers?.get("content-type");

      if (contentType?.includes("application/json")) {
        const data = await res.json();
        const authToken = data?.token || data?.access_token;
        if (authToken) {
          const isHttps = typeof window !== "undefined" && window.location.protocol === "https:";
          Cookies.set("access_token", authToken, {
            expires: 1,
            secure: isHttps,
            sameSite: "Lax",
          });
          setToken(authToken);
          if (data?.user) {
            setUser(data.user);
            try {
              localStorage.setItem("auth_user", JSON.stringify(data.user));
            } catch (e) {}
          }
          return { success: true, user: data?.user, token: authToken };
        } else {
          console.warn("No access_token or token found in response", data);
          return { success: false, message: data?.message || "No token returned" };
        }
      }
      return { success: false, message: "Invalid response format" };
    } catch (err) {
      console.error("Login error:", err);
      return { success: false, error: err.message };
    } finally {
      setLoading(false);
    }
  };

  const logoutUser = () => {
    Cookies.remove("access_token");
    setToken(null);
    setUser(null);
    if (typeof window !== "undefined") {
      try {
        localStorage.removeItem("auth_user");
      } catch (e) {}
    }
  };

  useEffect(() => {
    if (!token) {
      loginUser("admin@hotelres.com", "12345678");
    } else {
      setLoading(false);
    }
  }, []);

  // Fetch categories once token is ready
  useEffect(() => {
    if (!token) return;
    const fetchCategories = async () => {
      try {
        const res = await fetch(
          `${API_BASE_URL}${APIENDPOINTS.COURSE_CATEGORIES}`,
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              Accept: "application/json",
              Authorization: `Bearer ${token}`,
            },
            body: JSON.stringify({
              country_id: active_country,
            }),
          },
        );

        if (!res?.ok) throw new Error(`HTTP error! Status: ${res?.status}`);
        const data = await res.json();
        if (data?.data) {
          setCategories(data.data);
          localStorage.setItem("categories", JSON.stringify(data.data));
        }
      } catch (err) {
        console.error("Error fetching categories:", err);
      }
    };
    fetchCategories();
  }, [token]);

  const [countryLists, setcoutryLists] = useState([]);
  // console.log("countryLists------main", countryLists);
  useEffect(() => {
    if (!token) return;
    const FetchCountryList = async () => {
      try {
        const res = await fetch(`${API_BASE_URL}${APIENDPOINTS.COUNTRY_LIST}`, {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            Authorization: `Bearer ${token}`,
          },
        });
        if (!res.ok) {
          setcoutryLists([]);
          return;
        }
        const data = await res.json();
        setcoutryLists(data?.data || []);
      } catch (err) {
        console.error("Error fetching CountryList:", err);
      }
    };
    FetchCountryList();
  }, [token]);

  const [promoList, setPromoList] = useState(null);
  useEffect(() => {
    if (!token) return;
    const Fetchpromo = async () => {
      try {
        const res = await fetch(`${API_BASE_URL}${APIENDPOINTS.PROMO_LIST}`, {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            Authorization: `Bearer ${token}`,
          },
        });
        if (!res.ok) {
          console.warn("HTTP error! Status:", res.status);
          setPromoList(null);
          return;
        }
        const data = await res.json();
        if (data?.data?.length > 0) {
          setPromoList(data.data);
        }
      } catch (err) {
        console.error("Error fetching promoList:", err);
      }
    };
    Fetchpromo();
  }, [token]);

  return (
    <AuthContext.Provider
      value={{
        token,
        user,
        loading,
        login: loginUser,
        logout: logoutUser,
        categories,
        countryLists,
        promoList,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => useContext(AuthContext);
