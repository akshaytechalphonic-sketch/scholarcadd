import React, { useEffect, useMemo, useState } from "react";
import Modal from "./modal";
import { RiEdit2Line, RiRefreshLine } from "react-icons/ri";
import { HiOutlineArrowNarrowRight } from "react-icons/hi";
import { useAuth } from "@/context/AuthContext";
import { API_BASE_URL, APIENDPOINTS } from "../../apiconfig";
import toast from "react-hot-toast";
import { GoCheckCircleFill } from "react-icons/go";
import { FiRefreshCcw, FiSend } from "react-icons/fi";
import Link from "next/link";

const FormModal = ({ buttonText, modalType }) => {
  const { token } = useAuth();
  const { countryLists } = useAuth();
  const [userLocation, setUserLocation] = useState(null);

  useEffect(() => {
    const saved = sessionStorage.getItem("userLocation");
    if (saved) {
      try {
        setUserLocation(JSON.parse(saved));
      } catch (e) {}
    }
  }, []);

  const active_country = useMemo(() => {
    return userLocation?.raw?.country.toUpperCase() || "IN";
  }, [userLocation]);

  const matchedCountry = countryLists.find(
    (c) => c.iso2?.toUpperCase() === active_country?.toUpperCase(),
  );

  const defaultPhoneCode = matchedCountry
    ? matchedCountry.phonecode.includes("-")
      ? matchedCountry.phonecode
      : `+${matchedCountry.phonecode}`
    : "+91";

  const [open, setOpen] = useState(false);
  const [formData, setFormData] = useState({
    full_name: "",
    email: "",
    country_code: "",
    phone: "",
    message: "",
    service: buttonText,
    accepted_terms: false,
  });
  const [errors, setErrors] = useState({});

  // --- OTP states ---
  const [otpSent, setOtpSent] = useState(false);
  const [otpValue, setOtpValue] = useState("");
  const [otpVerified, setOtpVerified] = useState(false);
  const [otpExpired, setOtpExpired] = useState(false);
  const [otpTimer, setOtpTimer] = useState(120);
  const [emailValidating, setEmailValidating] = useState(false);

  // --- Handle default country code ---
  useEffect(() => {
    if (defaultPhoneCode) {
      setFormData((prev) => ({ ...prev, country_code: defaultPhoneCode }));
    }
  }, [defaultPhoneCode]);

  // --- Validation functions ---
  const validateField = (name, value) => {
    let error = "";
    switch (name) {
      case "full_name":
        if (!value.trim()) error = "Full name is required.";
        break;
      case "email":
        if (!value) error = "Email is required.";
        else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value))
          error = "Enter a valid email.";
        break;
      // case "phone":
      //   if (!value) error = "Phone number is required.";
      //   else if (!/^[0-9]{10}$/.test(value))
      //     error = "Phone number must be exactly 10 digits.";
      //   break;
      case "terms":
        if (!value) error = "You must accept the Terms and Conditions.";
        break;
      default:
        break;
    }
    setErrors((prev) => ({ ...prev, [name]: error }));
  };

  const validateAll = () => {
    const newErrors = {};
    if (!formData.full_name.trim())
      newErrors.full_name = "Full name is required.";
    if (!formData.email) newErrors.email = "Email is required.";
    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.email))
      newErrors.email = "Enter a valid email.";
    // if (!formData.phone) newErrors.phone = "Phone number is required.";
    // else if (!/^[0-9]{10}$/.test(formData.phone))
    //   newErrors.phone = "Phone number must be exactly 10 digits.";
    if (!formData.terms)
      newErrors.terms = "You must accept the Terms and Conditions.";
    if (!otpVerified) newErrors.otp = "Please verify your email OTP first.";
    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;
    let newValue = type === "checkbox" ? checked : value;
    if (name === "phone") newValue = newValue.replace(/\D/g, "").slice(0, 10);
    setFormData((prev) => ({ ...prev, [name]: newValue }));
    validateField(name, newValue);
  };

  // --- OTP Timer countdown ---
  useEffect(() => {
    if (!otpSent || otpVerified) return;

    if (otpTimer <= 0) {
      setOtpExpired(true);
      return;
    }

    const timer = setInterval(() => {
      setOtpTimer((prev) => prev - 1);
    }, 1000);

    return () => clearInterval(timer);
  }, [otpSent, otpVerified, otpTimer]);

  const [otpLoading, setOtpLoading] = useState(false);
  const sendOtp = async () => {
    if (!formData.email || errors.email) {
      toast("Enter a valid email before sending OTP", { icon: "⚠️" });
      return;
    }
    if (otpLoading) return;
    setOtpLoading(true);
    setEmailValidating(true);
    try {
      const res = await fetch(`${API_BASE_URL}${APIENDPOINTS.SEND_OTP}`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({ email: formData.email }),
      });

      const data = await res.json();
      if (res.status === 200) {
        setOtpSent(true);
        setOtpVerified(false);
        setOtpExpired(false);
        setOtpTimer(120);
        toast.success("OTP sent successfully!");
      } else {
        toast.error(data?.message || "Failed to send OTP");
      }
    } catch (err) {
      toast.error("Something went wrong while sending OTP");
    } finally {
      setOtpLoading(false);
      setEmailValidating(false);
    }
  };

  // --- Verify OTP ---
  const verifyOtp = async () => {
    if (otpValue.length === 0) return;
    if (!otpValue) {
      toast("Enter OTP", { icon: "⚠️" });
      return;
    }
    try {
      const res = await fetch(`${API_BASE_URL}${APIENDPOINTS.VERIFY_OTP}`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({ email: formData.email, otp: otpValue }),
      });
      const data = await res.json();
      if (res.status === 200) {
        setOtpVerified(true);
        toast.success("OTP verified successfully!");
      } else {
        toast.error(data.message || "OTP verification failed");
      }
    } catch (err) {
      toast.error("Something went wrong during OTP verification");
    }
  };
  // --- Submit form after OTP verification ---
  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!validateAll()) return;
    const isCurriculumService = formData?.service === "Download Curriculum";
    try {
      const res = await fetch(
        `${API_BASE_URL}${APIENDPOINTS.STORE_REQUEST_FOR_SEVICE}`,
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${token}`,
          },
          body: JSON.stringify(formData),
        },
      );
      const data = await res.json();
      if (res.status === 201) {
        toast.success("Thanks for contacting us! We will reach you soon.");
        //  set session ONLY when required
        if (isCurriculumService) {
          sessionStorage.setItem("curriculum_download", "1");
          sessionStorage.setItem("curriculum_time", Date.now().toString());
          window.dispatchEvent(new Event("curriculum-download-ready"));
        }
        setOpen(false);
        // Reset form
        setFormData({
          full_name: "",
          email: "",
          country_code: defaultPhoneCode,
          phone: "",
          message: "",
          service: "",
          accepted_terms: false,
        });
        // Reset OTP states
        setOtpSent(false);
        setOtpValue("");
        setOtpVerified(false);
        setOtpTimer(0);
      } else {
        toast.error(data.message || "Failed to submit form");
      }
    } catch (err) {
      console.error(err);
      toast.error("Something went wrong while submitting form");
    }
  };

  // --- Render modal content ---
  const renderModalContent = () => {
    switch (modalType) {
      case "register":
        return (
          <form className="space-y-3" onSubmit={handleSubmit} noValidate>
            <p className="para">
              Please share your details, and our team will get in touch with
              you.
            </p>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <input
                  type="text"
                  name="full_name"
                  placeholder="Full Name *"
                  value={formData.full_name}
                  onChange={handleChange}
                  className="form-input"
                />
                {errors.full_name && (
                  <p className="text-red-500 text-sm mt-1">
                    {errors.full_name}
                  </p>
                )}
              </div>

              <div>
                <div className="grid grid-cols-[30%_70%] gap-2">
                  <select
                    name="country_code"
                    value={formData.country_code}
                    onChange={handleChange}
                    className="col-span-1 form-input"
                  >
                    {countryLists?.map((item, index) => {
                      const code = item?.phonecode?.includes("-")
                        ? item?.phonecode
                        : `+${item?.phonecode}`;
                      return (
                        <option key={index} value={code}>
                          {code}
                        </option>
                      );
                    })}
                  </select>

                  <input
                    type="tel"
                    name="phone"
                    placeholder="Phone Number"
                    value={formData.phone}
                    onChange={(e) => {
                      let value = e.target.value;
                      if (!/^[+\d]*$/.test(value)) return;
                      const digitsOnly = value.replace("+", "");
                      if (digitsOnly.length > 10) return;
                      handleChange(e);
                    }}
                    className="form-input"
                  />
                </div>
                {errors.phone && (
                  <p className="text-red-500 text-sm">{errors.phone}</p>
                )}
              </div>
            </div>

            <div className="grid grid-cols-[75%_20%] gap-2 items-center">
              <input
                type="email"
                name="email"
                placeholder="Email *"
                value={formData.email}
                onChange={handleChange}
                className="form-input w-full"
                disabled={otpVerified}
              />

              {/* If verified — show verified message */}
              {otpVerified ? (
                <span className="text-green-600 flex gap-1 items-center">
                  <GoCheckCircleFill /> OTP Verified
                </span>
              ) : (
                // <button
                //   type="button"
                //   onClick={sendOtp}
                //   disabled={!otpExpired && otpSent}
                //   className={`px-3 py-2 text-white rounded-full  text-sm flex items-center gap-2
                //     ${
                //       otpExpired || !otpSent
                //         ? "bg-blue-500 hover:bg-blue-600 cursor-pointer"
                //         : "bg-gray-400 cursor-not-allowed"
                //     }
                //   `}
                // >
                //   {otpSent ? (
                //     <>
                //       <FiRefreshCcw /> Resend OTP
                //     </>
                //   ) : (
                //     <>
                //       <FiSend /> Verify OTP
                //     </>
                //   )}
                // </button>
                <button
                  type="button"
                  onClick={sendOtp}
                  disabled={otpLoading || (!otpExpired && otpSent)}
                  className={`px-3 py-2 text-white rounded-full text-sm
                    ${
                      otpLoading
                        ? "bg-gray-400 cursor-not-allowed"
                        : otpExpired || !otpSent
                          ? "bg-[#882CFB] hover:bg-[#4347ca] cursor-pointer "
                          : "bg-gray-400 cursor-not-allowed"
                    }
                  `}
                >
                  {otpLoading
                    ? "Sending OTP..."
                    : otpSent
                      ? "Resend OTP"
                      : "Verify OTP"}
                </button>
              )}
            </div>

            {/* OTP Input Section */}
            {otpSent && !otpVerified && (
              <div className="flex flex-col gap-2 mt-3">
                <div className="grid grid-cols-[75%_20%] gap-2 items-center">
                  <input
                    type="text"
                    placeholder="Enter OTP"
                    value={otpValue}
                    onChange={(e) => setOtpValue(e.target.value)}
                    className="form-input w-full"
                    disabled={otpExpired}
                  />

                  <button
                    type="button"
                    onClick={verifyOtp}
                    disabled={otpExpired}
                    className={`px-3 py-2 text-white rounded-full text-sm flex gap-1 items-center justify-center
                      ${
                        otpExpired
                          ? "bg-gray-400 cursor-not-allowed"
                          : "bg-green-500 hover:bg-green-700"
                      }
                    `}
                  >
                    <GoCheckCircleFill /> Verify OTP
                  </button>
                </div>
                {otpSent && !otpVerified && otpTimer > 0 && (
                  <span className="text-red-500 text-sm">
                    OTP expires in: {Math.floor(otpTimer / 60)}:
                    {("0" + (otpTimer % 60)).slice(-2)}
                  </span>
                )}

                {otpExpired && (
                  <span className="text-red-600 text-sm font-medium">
                    OTP expired! Please resend OTP.
                  </span>
                )}
              </div>
            )}
            {errors.otp && <p className="text-red-500 text-sm">{errors.otp}</p>}
            <textarea
              rows="3"
              name="message"
              maxLength={500}
              placeholder="Message"
              value={formData.message}
              onChange={handleChange}
              className="form-input"
            ></textarea>

            {errors.terms && (
              <p className="text-red-500 text-sm">{errors.terms}</p>
            )}
            <div className="flex items-center justify-between gap-2">
              <div className="flex items-start gap-1">
                <input
                  type="checkbox"
                  name="terms"
                  checked={formData.terms}
                  onChange={handleChange}
                  className="mt-1 cursor-pointer"
                />
                <p className="text-sm text-gray-500 px-2">
                  I agree to{" "}
                  <Link
                    target="_blank"
                    href={{
                      pathname: `/term-and-condition`,
                    }}
                    className="font-semibold text-indigo-600 hover:underline"
                  >
                    Terms and Conditions
                  </Link>
                  .
                </p>
              </div>
              <div className="flex justify-end">
                <button
                  type="submit"
                  className="relative flex items-center justify-between px-4 py-2 text-sm cursor-pointer  border-2 border-white text-white rounded-full bg-[#882CFB] hover:bg-blue-700 gap-1 hover:brightness-110 transition"
                >
                  <span className="select-none">Submit</span>
                  <HiOutlineArrowNarrowRight />
                </button>
              </div>
            </div>
          </form>
        );
      default:
        return <p>Modal content not found</p>;
    }
  };

  return (
    <>
      <button
        type="button"
        onClick={() => setOpen(true)}
        className="relative group border-none bg-transparent p-0 cursor-pointer"
      >
        <div className="relative flex items-center justify-center py-2 px-4 border-2 border-white text-white rounded-full bg-[#882CFB] hover:bg-[#4347ca] gap-1 transition group-hover:brightness-110">
          <span className="select-none">{buttonText}</span>
          <HiOutlineArrowNarrowRight />
        </div>
      </button>

      <Modal
        isOpen={open}
        onClose={() => setOpen(false)}
        title={`Request for ${buttonText}`}
        width="w-full max-w-sm md:max-w-2xl"
        position="center"
      >
        {renderModalContent()}
      </Modal>
    </>
  );
};

export default FormModal;
