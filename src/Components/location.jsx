import React, { useEffect, useState } from "react";
import {
  HiOutlineGlobeAlt,
  HiLocationMarker,
  HiOutlineIdentification,
} from "react-icons/hi";

const IP_LOOKUP_URL = "https://ipapi.co/json/";
const REVERSE_GEOCODE = (lat, lon) =>
  `https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${lat}&longitude=${lon}&localityLanguage=en`;

const RATE_LIMIT = 900;
const RATE_WINDOW = 24 * 60 * 60 * 1000;
const STORAGE_KEY = "userLocation";

export default function HybridLocation({ onLocation }) {
  const [loading, setLoading] = useState(true);
  const [status, setStatus] = useState("Initializing...");
  const [location, setLocation] = useState(null);
  const [showModal, setShowModal] = useState(false);

  useEffect(() => {
    const cached = sessionStorage.getItem(STORAGE_KEY);
    if (cached) {
      const loc = JSON.parse(cached);
      setLocation(loc);
      setLoading(false);
      if (onLocation) onLocation(loc);
      return;
    }
    //  API Rate Limit (900 hits per 24 hours)
    const RATE_KEY = "location_api_hits";
    const RATE_TIME_KEY = "location_api_timestamp";
    const prevHits = parseInt(sessionStorage.getItem(RATE_KEY) || "0");
    const lastTimestamp = parseInt(
      sessionStorage.getItem(RATE_TIME_KEY) || "0"
    );
    const now = Date.now();
    // Reset if 24hrs passed
    if (!lastTimestamp || now - lastTimestamp >= RATE_WINDOW) {
      sessionStorage.setItem(RATE_KEY, "0");
      sessionStorage.setItem(RATE_TIME_KEY, now.toString());
      console.log(" Rate-limit reset: starting new 24-hour cycle.");
    } else {
      // If limit reached → stop API call
      if (prevHits >= RATE_LIMIT) {
        console.log(
          ` API hit limit reached (${prevHits}/${RATE_LIMIT}). No more IP lookups until reset.`
        );
        setLoading(false);
        return;
      }
    }
    // Allowed → increase hit count
    const newCount = prevHits + 1;
    sessionStorage.setItem(RATE_KEY, newCount.toString());
    // console.log(` IP API Hit Count: ${newCount}/${RATE_LIMIT}`);
    // Try IP-based detection
    (async () => {
      try {
        setStatus("Detecting location via IP...");
        const res = await fetch(IP_LOOKUP_URL);
        if (!res.ok) throw new Error("IP lookup failed");
        const data = await res.json();
        const ipLocation = {
          source: "ip",
          ip: data?.ip || "",
          country: data?.country_name || "",
          state: data?.region || "",
          city: data?.city || "",
          raw: data,
        };
        setLocation(ipLocation);
        sessionStorage.setItem(STORAGE_KEY, JSON.stringify(ipLocation));
        setStatus("IP-based location detected");
        setLoading(false);
        if (onLocation) onLocation(ipLocation);
      } catch (err) {
        console.warn("IP detection failed, forcing user location...");
        // IP detection failed, force modal
        // setShowModal(true);
        setLoading(false);
      }
    })();
  }, [onLocation]);

  const requestBrowserLocation = () => {
    if (!navigator.geolocation) {
      setStatus("Geolocation not supported.");
      return;
    }
    setStatus("Requesting precise location...");
    navigator.geolocation.getCurrentPosition(
      async (pos) => {
        const { latitude, longitude } = pos.coords;
        setStatus("Fetching location details...");
        try {
          const res = await fetch(REVERSE_GEOCODE(latitude, longitude));
          const data = await res.json();
          const gpsLocation = {
            source: "gps",
            country: data.countryName || "",
            state: data.principalSubdivision || data.locality || "",
            city: data.city || "",
            latitude,
            longitude,
            raw: data,
          };
          setLocation(gpsLocation);
          sessionStorage.setItem(STORAGE_KEY, JSON.stringify(gpsLocation));
          setShowModal(false);
          setStatus("Location detected accurately");
          if (onLocation) onLocation(gpsLocation);
        } catch (e) {
          setStatus("Failed to fetch location details.");
        }
      },
      (err) => {
        if (err.code === 1) setStatus("Permission denied.");
        else if (err.code === 2) setStatus("Position unavailable.");
        else if (err.code === 3) setStatus("Timeout.");
        else setStatus("Unable to get location.");
      },
      { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    );
  };

  return (
    <>
      {/* <div className=" bg-yellow-50 text-sm p-2">
        {location && (
          <div className="flex md:flex-row flex-col items-center justify-center space-x-6   ">
            <div className="flex items-center space-x-1">
              <HiOutlineGlobeAlt className="text-gray-400 " />
              <span className="text-gray-500 text-xs">Source :</span>
              <span className="font-medium text-gray-800 capitalize">
                {location.source}
              </span>
            </div>
            <div className="flex items-center space-x-1">
              <HiLocationMarker className="text-blue-500 " />
              <span className="text-gray-500 text-xs">Location :</span>
              <span className="font-medium text-gray-800 truncate">
                {location.city ? `${location.city}, ` : ""}
                {location.state ? `${location.state}, ` : ""}
                {location.country}
              </span>
            </div>
            {location.ip && (
              <div className="flex items-center space-x-1">
                <HiOutlineIdentification className="text-green-500 " />
                <span className="text-gray-500 text-xs">IP :</span>
                <span className="font-medium text-gray-800">{location.ip}</span>
              </div>
            )}
          </div>
        )}
      </div> */}

      {/* Modal to force user location */}
      {showModal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
          <div className="bg-white p-6 rounded-xl shadow-lg w-11/12 max-w-md text-center animate-fade-in">
            <h2 className="text-lg font-semibold mb-2">
              Allow Location Access
            </h2>
            <p className="text-sm text-gray-700 mb-4">
              We could not detect your location automatically. Please allow
              access to get accurate country, state, and city information.
            </p>
            <button
              onClick={requestBrowserLocation}
              className="bg-blue-600 text-white w-full py-2 rounded hover:bg-blue-700"
            >
              Allow Location
            </button>
          </div>
          <style jsx>{`
            .animate-fade-in {
              animation: fadeIn 0.3s ease-out forwards;
            }
            @keyframes fadeIn {
              0% {
                opacity: 0;
                transform: scale(0.95);
              }
              100% {
                opacity: 1;
                transform: scale(1);
              }
            }
          `}</style>
        </div>
      )}
    </>
  );
}
