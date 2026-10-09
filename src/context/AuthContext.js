import { createContext, useContext, useState, useEffect, useMemo } from "react";
import { API_BASE_URL, APIENDPOINTS } from "../../apiconfig";
const AuthContext = createContext();
import Cookies from "js-cookie";

export const AuthProvider = ({ children }) => {
  const [token, setToken] = useState(null);
  const [loading, setLoading] = useState(true);
  const [categories, setCategories] = useState([]);
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

  useEffect(() => {
    const login = async () => {
      try {
        const res = await fetch(`${API_BASE_URL}${APIENDPOINTS.LOGIN}`, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            email: "scholaracadadmin@amysoftech.in",
            password: "12345678",
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
          if (data?.access_token) {
            const isHttps = typeof window !== "undefined" && window.location.protocol === "https:";
            Cookies.set("access_token", data.access_token, {
              expires: 1,
              secure: isHttps,
              sameSite: "Lax",
            });
            setToken(data?.access_token);
          } else {
            console.warn("No access_token found in response");
          }
        }
      } catch (err) {
        console.error("Login error:", err);
      } finally {
        setLoading(false);
      }
    };
    login();
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
      value={{ token, loading, categories, countryLists, promoList }}
    >
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => useContext(AuthContext);
