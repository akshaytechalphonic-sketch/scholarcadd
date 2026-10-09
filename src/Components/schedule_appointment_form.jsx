import React, { useEffect, useMemo, useState } from "react";
import { API_BASE_URL, APIENDPOINTS } from "../../apiconfig";
import { useAuth } from "@/context/AuthContext";
import toast from "react-hot-toast";
import Link from "next/link";
import { HiMiniArrowLongRight } from "react-icons/hi2";

function ScheduleAppointment() {
  const { token } = useAuth();
  const { categories } = useAuth();
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
    return userLocation?.raw?.country.toUpperCase() || "in";
  }, [userLocation]);

  const active_state = useMemo(() => {
    return userLocation?.state || "Delhi";
  }, [userLocation]);

  const [countryLists, setcoutryLists] = useState([]);
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
          console.warn("HTTP error! Status:", res?.status);
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

  const [loading, setLoading] = useState(false);
  const [formData, setFormData] = useState({
    full_name: "",
    email: "",
    phone: "",
    country_id: "",
    country_code: "",
    course_id: "",
    city: "",
    message: "",
    agreed: 0,
    image: null,
  });

  const handleChange = (e) => {
    const { name, value, type, checked, selectedOptions } = e.target;
    setFormData((prev) => ({
      ...prev,
      [name]: type === "checkbox" ? (checked ? 1 : 0) : value,
      ...(name === "country_id"
        ? {
            city: "",
            country_code: selectedOptions[0]?.getAttribute("data-iso2") || "",
          }
        : {}),
    }));
  };
  const handleSubmit = async (e) => {
    e.preventDefault();
    const requiredFields = [
      "full_name",
      "email",
      "course_id",
      "country_id",
      "message",
    ];
    for (let field of requiredFields) {
      if (!formData[field] || formData[field].trim() === "") {
        toast.error("Please fill out all required fields.");
        setLoading(false);
        return;
      }
    }
    // **Phone Validation (min 5 digits, max 10 digits)**
    // if (!/^[0-9]{5,10}$/.test(formData.phone)) {
    //   toast.error("Phone number must be between 5 and 10 digits.");
    //   setLoading(false);
    //   return;
    // }
    // Message Word Count Validation (max 500 words)
    const wordCount = formData.message.trim().split(/\s+/).length;
    if (wordCount > 500) {
      toast.error("Message cannot exceed 500 words.");
      setLoading(false);
      return;
    }
    if (formData.agreed !== 1) {
      toast.error("Please agree to the terms before submitting.");
      return;
    }
    setLoading(true);
    try {
      const res = await fetch(
        `${API_BASE_URL}${APIENDPOINTS.APPOINTMENT_FORM}`,
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            Authorization: `Bearer ${token}`,
          },
          body: JSON.stringify(formData),
        }
      );
      if (!res.ok) {
        toast.error("Something went wrong. Please try again.");
        // toast.error(`Error: ${res.status}`);
        return;
      }
      const data = await res.json();
      toast.success("Thanks for scheduling an appointment! We will reach you soon.");
      setFormData({
        full_name: "",
        enquiryType: "",
        email: "",
        country_id: "",
        course_id: "",
        phone: "",
        deliveryMode: "",
        contactMode: "",
        message: "",
        agreed: 0,
        image: null,
      });
    } catch (err) {
      toast.error("Submission failed. Try again later.");
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const [citylist, setcitylist] = useState([]);
  const [cityLoading, setCityLoading] = useState(false);
  useEffect(() => {
    if (!token || !formData.country_code) {
      setcitylist([]);
      return;
    }
    const fetchCity = async () => {
      setCityLoading(true);
      try {
        const res = await fetch(`${API_BASE_URL}${APIENDPOINTS.CITY_LIST}`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            Authorization: `Bearer ${token}`,
          },
          body: JSON.stringify({
            country_id: formData.country_code,
            state_id: null,
          }),
        });

        if (!res.ok) {
          setcitylist([]);
          setCityLoading(false);
          return;
        }
        const data = await res.json();
        setcitylist(data?.data || []);
      } catch (err) {
        setcitylist([]);
      } finally {
        setCityLoading(false);
      }
    };
    fetchCity();
  }, [token, formData.country_code]);

  return (
    <>
      <div>
        <form
          className="p-3 rounded-2xl shadow-md space-y-6"
          onSubmit={handleSubmit}
        >
          <div className="flex md:flex-row flex-col gap-2 justify-between items-center">
            <h4 className="heading_white">Schedule an appointment</h4>
            <img src="/assets/landingpage/googlerating.svg" alt="" />
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input
              type="text"
              name="full_name"
              placeholder="Full Name*"
              value={formData?.full_name}
              onChange={handleChange}
              className="border border-gray-300 rounded-md p-2 w-full bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />

            <input
              type="email"
              name="email"
              placeholder="Email address*"
              value={formData?.email}
              onChange={handleChange}
              className="border border-gray-300 rounded-md p-2 w-full bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />

            <input
              type="text"
              name="phone"
              placeholder="Phone Number"
              value={formData?.phone}
              onChange={(e) => {
                let value = e.target.value;
                if (!/^[+\d]*$/.test(value)) return;
                const digitsOnly = value.replace("+", "");
                if (digitsOnly.length > 10) return;
                handleChange(e);
              }}
              className="border border-gray-300 rounded-md p-2 w-full bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />

            <select
              name="country_id"
              value={formData.country_id}
              onChange={handleChange}
              className="border border-gray-300 rounded-md p-2 w-full bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Select Country*</option>
              {countryLists?.map((country) => (
                <option
                  key={country.id}
                  value={country.id}
                  data-iso2={country.iso2}
                >
                  {country.name}
                </option>
              ))}
            </select>
            <select
              name="city"
              value={formData?.city}
              onChange={handleChange}
              className="border border-gray-300 rounded-md p-2 w-full bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">
                {cityLoading ? "Loading cities..." : "Select City*"}
              </option>
              {cityLoading && <option disabled>Loading...</option>}
              {!cityLoading && citylist.length === 0 && (
                <option disabled>No city available</option>
              )}
              {!cityLoading &&
                citylist.length > 0 &&
                citylist.map((city) => (
                  <option key={city?.name} value={city?.name}>
                    {city?.name}
                  </option>
                ))}
            </select>

            <select
              name="course_id"
              value={formData?.course_id}
              onChange={handleChange}
              className="border border-gray-300 rounded-md p-2 w-full bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Select Course*</option>
              {categories?.flatMap((category) =>
                category?.cources?.map((course) => (
                  <option key={course?.course_id} value={course?.id}>
                    {course?.course_short_name}
                  </option>
                ))
              )}
            </select>
          </div>

          <textarea
            name="message"
            rows="4"
            maxLength={500}
            placeholder="Enter Your Training Requirements*"
            value={formData?.message}
            onChange={handleChange}
            className="border border-gray-300 rounded-md p-2 w-full bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
          ></textarea>

          <div className="grid grid-cols-1 md:grid-cols-[80%_20%] items-center w-full">
            <div className="flex items-start gap-2">
              <input
                type="checkbox"
                name="agreed"
                checked={formData?.agreed === 1}
                onChange={handleChange}
                className="w-4 h-4 cursor-pointer"
              />
              <label className="text-white text-sm">
                I agree to receive communications and accept{" "}
                <Link
                  target="_blank"
                  href={{
                    pathname: `/term-and-condition`,
                  }}
                  className="font-semibold text-white hover:underline"
                >
                  Terms and Conditions
                </Link>
              </label>
            </div>
            <div className="flex justify-end items-end">
              <button
                type="submit"
                disabled={loading}
                className="bg-[#882CFB] border-2 border-white  hover:bg-[#178bbd] px-4 w-full text-white py-3 rounded-full cursor-pointer transition-colors duration-200 flex gap-2 items-center justify-center"
              >
                {loading ? "Sending..." : "Submit"}{" "}
                <HiMiniArrowLongRight />
              </button>
            </div>
          </div>
        </form>
      </div>
    </>
  );
}

export default ScheduleAppointment;
