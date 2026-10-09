import { useState, useEffect } from "react";
import { FaCookieBite } from "react-icons/fa";
import { IoCloseCircleOutline, IoCloseOutline } from "react-icons/io5";

export default function CookieConsent() {
  const [visible, setVisible] = useState(false);
  const setCookie = (name, value, days) => {
    const expires = new Date(Date.now() + days * 864e5).toUTCString();
    document.cookie = name + "=" + value + "; expires=" + expires + "; path=/";
  };
  const getCookie = (name) => {
    return document.cookie
      .split("; ")
      .find((row) => row.startsWith(name + "="))
      ?.split("=")[1];
  };
  useEffect(() => {
    const consent = getCookie("userConsent");
    if (!consent) {
      setTimeout(() => setVisible(true), 1000);
    }
  }, []);

  const handleAllowAll = () => {
    setCookie("userConsent", "all", 365);
    setVisible(false);
  };
  const handlePartial = () => {
    setCookie("userConsent", "necessary", 365);
    setVisible(false);
  };
  const handleClose = () => {
    setVisible(false);
  };
  return visible ? (
    <div className="fixed bottom-0 left-0 right-0 z-50  items-end p-3">
      <div
        className="absolute inset-0 bg-blac opacity-30"
        onClick={handleClose}
      ></div>
      <div className="relative bg-white rounded-lg shadow-xl p-4 max-w-xl  w-full transition-transform duration-300 translate-y-0">
        <div className="flex items-center justify-between mb-3">
          <div className="flex items-center gap-3">
            {/* <FaCookieBite className="text-yellow-500 text-2xl" /> */}
            <h2 className="Sub_heading_black">Cookie Consent</h2>
          </div>
          <button
            className="text-gray-400 hover:text-gray-600"
            onClick={handleClose}
            title="Close"
          >
            <IoCloseOutline title="Close"  className="text-2xl cursor-pointer" />
          </button>
        </div>
        <p className="para">
          We use cookies to improve your experience and provide personalized
          content. You can allow all cookies or allow only necessary cookies.
        </p>
        <div className="flex flex-wrap md:justify-end gap-3 mt-5">
          <button
            className="flex items-center gap-2 px-3 py-2 text-xs rounded-lg border border-gray-300 hover:bg-gray-100 text-gray-700 font-medium"
            onClick={handlePartial}
          >
            <span>Necessary Only</span>
          </button>
          <button
            className="flex items-center gap-2 px-3 py-2 text-xs rounded-lg bg-green-600 text-white hover:bg-green-700 font-medium"
            onClick={handleAllowAll}
          >
            {/* <FaCookieBite /> */}
             Allow All
          </button>
        </div>
      </div>
    </div>
  ) : null;
}
