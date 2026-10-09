import React, { useState } from "react";
import Modal from "../Components/modal";
import { RiEdit2Line, RiRefreshLine } from "react-icons/ri";
function register() {
  const [openModal, setOpenModal] = useState(null);
  const [formData, setFormData] = useState({
    fullName: "",
    email: "",
    countryCode: "+91",
    phone: "",
    message: "",
    terms: false,
  });
  const [errors, setErrors] = useState({});
  const validateField = (name, value) => {
    let error = "";
    switch (name) {
      case "fullName":
        if (!value.trim()) error = "Full name is required.";
        break;
      case "email":
        if (!value) error = "Email is required.";
        else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value))
          error = "Enter a valid email.";
        break;
      case "phone":
        if (!value) error = "Phone number is required.";
        else if (!/^[0-9]{10}$/.test(value))
          error = "Phone number must be exactly 10 digits.";
        break;
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
    if (!formData.fullName.trim()) {
      newErrors.fullName = "Full name is required.";
    }
    if (!formData.email) {
      newErrors.email = "Email is required.";
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.email)) {
      newErrors.email = "Enter a valid email.";
    }
    if (!formData.phone) {
      newErrors.phone = "Phone number is required.";
    } else if (!/^[0-9]{10}$/.test(formData.phone)) {
      newErrors.phone = "Phone number must be exactly 10 digits.";
    }
    if (!formData.terms) {
      newErrors.terms = "You must accept the Terms and Conditions.";
    }
    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };
  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;
    let newValue = type === "checkbox" ? checked : value;
    if (name === "phone") {
      newValue = newValue.replace(/\D/g, "").slice(0, 10);
    }
    setFormData((prev) => ({
      ...prev,
      [name]: newValue,
    }));
    validateField(name, newValue);
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    if (validateAll()) {
      alert("Form submitted successfully!");
      console.log("Form Data:", formData);
    }
  };

  return (
    <>
      <div className=" flex items-center justify-center bg-gray-100">
        {/* Open Modal Button for Register */}
        <button
          onClick={() => setOpenModal("register")}
          className="px-5 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700"
        >
          Open Modal Register
        </button>
        <Modal
          isOpen={openModal === "register"}
          onClose={() => setOpenModal(null)}
          title="Request For Download Syllabus"
          width="max-w-2xl"
          position="center"
        >
          <form className="space-y-5 mb-8" onSubmit={handleSubmit} noValidate>
            {/* Header */}
            <div>
              <h2 className="text-xl font-bold text-gray-800">
                Request For Download Syllabus
              </h2>
              <p className="text-gray-500 text-sm">
                All the essential details about the course at your fingertips.
              </p>
            </div>

            {/* Full Name + Email */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <input
                  type="text"
                  name="fullName"
                  placeholder="Full Name *"
                  value={formData.fullName}
                  onChange={handleChange}
                  className="form-input w-full"
                />
                {errors.fullName && (
                  <p className="text-red-500 text-sm mt-1">{errors.fullName}</p>
                )}
              </div>
              <div>
                <input
                  type="email"
                  name="email"
                  placeholder="Email *"
                  value={formData.email}
                  onChange={handleChange}
                  className="form-input w-full"
                />
                {errors.email && (
                  <p className="text-red-500 text-sm mt-1">{errors.email}</p>
                )}
              </div>
            </div>

            {/* Phone with Country Code */}
            <div className="grid grid-cols-4 gap-2">
              <select
                name="countryCode"
                value={formData.countryCode}
                onChange={handleChange}
                className="col-span-1 form-input"
              >
                <option value="+91">🇮🇳 +91</option>
                <option value="+1">🇺🇸 +1</option>
                <option value="+44">🇬🇧 +44</option>
                <option value="+61">🇦🇺 +61</option>
                <option value="+971">🇦🇪 +971</option>
              </select>
              <div className="col-span-3">
                <input
                  type="tel"
                  name="phone"
                  placeholder="Phone Number *"
                  value={formData.phone}
                  onChange={handleChange}
                  className="form-input w-full"
                />
                {errors.phone && (
                  <p className="text-red-500 text-sm mt-1">{errors.phone}</p>
                )}
              </div>
            </div>

            {/* Message */}
            <textarea
              rows="3"
              name="message"
              placeholder="Message"
              value={formData.message}
              onChange={handleChange}
              className="form-input w-full"
            ></textarea>

            <div className="flex justify-between items-center">
              <div className="flex items-start">
                <input
                  type="checkbox"
                  id="terms"
                  name="terms"
                  checked={formData.terms}
                  onChange={handleChange}
                  className="mt-1 cursor-pointer"
                />
                <p className="text-sm text-gray-500 px-2">
                  I agree to{" "}
                  <a href="/" className="font-semibold text-indigo-600">
                    Terms and Conditions
                  </a>
                  .
                </p>
              </div>
            </div>
            {errors.terms && (
              <p className="text-red-500 text-sm">{errors.terms}</p>
            )}

            {/* Submit Button */}
            <div className="flex justify-end">
              <button
                type="submit"
                title="Submit"
                className="px-6 py-2 bg-[#2E318D] cursor-pointer text-white font-semibold rounded-lg shadow hover:bg-indigo-700 transition"
              >
                Submit
              </button>
            </div>
          </form>
        </Modal>

        {/* Open Modal Button for Login */}
        {/* <button
          onClick={() => setOpenModal("login")}
          className="px-5 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700"
        >
          Open Modal Login
        </button> */}

        <Modal
          isOpen={openModal === "login"}
          onClose={() => setOpenModal(null)}
          title="Request For Login"
          width="max-w-lg"
          position="center"
        >
          <form className="space-y-5 mb-12">
            {/* Header */}
            <div>
              <h2 className="text-xl font-bold text-gray-800">Welcome!</h2>
              <p className="text-gray-500 text-sm">
                Sign up or Login to your account
              </p>
            </div>

            <div className="">
              <input
                type="text"
                placeholder="Enter your phone number or email"
                className="form-input"
                required
              />
            </div>

            <div className="flex justify-between items-center">
              <div className="">
                <div className="flex items-start gap-2">
                  <label htmlFor="whatsapp" className="text-sm text-gray-600">
                    By Signing up, you agree to our
                  </label>
                </div>
                <div className="flex gap-2">
                  <a
                    href="/"
                    className="font-semibold text-indigo-400 text-xs underline"
                  >
                    Terms and Conditions
                  </a>
                  <p className="text-sm text-gray-500">and</p>
                  <a
                    href="/"
                    className="font-semibold text-indigo-400 text-xs underline"
                  >
                    Privacy Policy
                  </a>
                </div>
              </div>
              {/* Submit Button */}
              <div className="">
                <button
                  type="submit"
                  title="Submit"
                  className="px-6 py-2  bg-[#2E318D] cursor-pointer text-white font-semibold rounded-lg shadow hover:bg-indigo-700 transition"
                >
                  Submit
                </button>
              </div>
            </div>
          </form>
        </Modal>

        {/* Open Modal Button for Validate OTP */}
        {/* <button
          onClick={() => setOpenModal("verifyotp")}
          className="px-5 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700"
        >
          Open Modal Validate
        </button> */}

        <Modal
          isOpen={openModal === "verifyotp"}
          onClose={() => setOpenModal(null)}
          title="Request For Verify OTP"
          width="max-w-lg"
          position="center"
        >
          <form className="space-y-5 mb-8">
            {/* Header */}
            <div>
              <h2 className="text-xl font-bold text-gray-800">Verify OTP</h2>
              <p className="text-gray-500 text-sm flex gap-2 items-center">
                We’ve sent an OTP to test@gmail.com
                <a
                  href=""
                  className="px-2 text-green-600 hover:underline font-semibold flex gap-1 items-center"
                >
                  <RiEdit2Line />
                  Edit
                </a>
              </p>
            </div>

            <div className="">
              <input
                type="text"
                placeholder="Enter OTP"
                className="form-input"
                required
              />
            </div>
            <div className="flex justify-between items-center">
              <div className="flex items-start gap-2">
                <p className="text-sm text-gray-600 flex gap-1 items-center">
                  Didn’t receive OTP? Time Left: 66s
                  <a
                    href=""
                    className="px-2 text-green-600 hover:underline font-semibold flex gap-1 items-center"
                  >
                    <RiRefreshLine />
                    Resend OTP
                  </a>
                </p>
              </div>
              {/* Submit Button */}
              <div className="">
                <button
                  type="submit"
                  title="Submit"
                  className="px-6 py-2 w-full bg-[#2E318D] cursor-pointer text-white font-semibold rounded-lg shadow hover:bg-indigo-700 transition"
                >
                  Continue
                </button>
              </div>
            </div>
          </form>
        </Modal>
      </div>
    </>
  );
}

export default register;
