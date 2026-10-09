import React, { useEffect, useState } from "react";
import { IoCallOutline } from "react-icons/io5";
import { TfiEmail } from "react-icons/tfi";
import { BsChatDots, BsPatchQuestion } from "react-icons/bs";
import Modal from "@/Components/modal";
import { useAuth } from "@/context/AuthContext";
import { API_BASE_URL, APIENDPOINTS } from "../../apiconfig";
import toast from "react-hot-toast";
import Link from "next/link";
import { HiMiniArrowLongRight } from "react-icons/hi2";

function GlobalTooltip() {
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [show, setShow] = useState(false);
  const phoneNumber = "9810812106";
  const emailAddress = "support@scholaracad.com";
  const whatsappNumber = "9350022106";

  useEffect(() => {
    const handleScroll = () => {
      if (window.scrollY > window.innerHeight * 0.5) {
        setShow(true);
      } else {
        setShow(false);
      }
    };

    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  const { token } = useAuth();
  const { categories } = useAuth();
  const { countryLists } = useAuth();

  // contact us form submit
  const [loading, setLoading] = useState(false);
  const [formData, setFormData] = useState({
    fullName: "",
    email: "",
    enquiryType: "",
    company_name: "",
    job_title: "",
    group_size: "",
    country: "",
    countryCode: "",
    phone: "",
    course: "",
    deliveryMode: "",
    contactMode: "",
    message: "",
    agree: "false",
  });

  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;
    setFormData((prev) => ({
      ...prev,
      [name]: type === "checkbox" ? (checked ? "true" : "false") : value,
    }));
    // phone code change from country select
    if (name === "country") {
      const selected = countryLists.find((item) => item.id == value);
      if (selected) {
        setFormData((prev) => ({
          ...prev,
          countryCode: selected.phonecode,
        }));
      } else {
        setFormData((prev) => ({
          ...prev,
          countryCode: "",
        }));
      }
    }
  };
  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    let requiredFields = [
      "fullName",
      "email",
      "enquiryType",
      "country",
      "course",
      "deliveryMode",
      "contactMode",
      "message",
    ];
    // If corporate -> ALL fields required
    if (formData.enquiryType === "corporate") {
      requiredFields.push("company_name", "job_title", "group_size");
    }
    // Validate
    for (let field of requiredFields) {
      if (!formData[field] || formData[field].trim() === "") {
        toast.error("Please fill out all required fields.");
        setLoading(false);
        return;
      }
    }
    // Validate checkbox separately
    if (formData.agree !== "true") {
      toast.error("You must agree to the terms.");
      setLoading(false);
      return;
    }
    // **Email Validation**
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(formData.email)) {
      toast.error("Please enter a valid email address.");
      setLoading(false);
      return;
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
    // **Phone Validation (min 5 digits, max 10 digits)**
    // if (!/^[0-9]{5,10}$/.test(formData.phone)) {
    //   toast.error("Phone number must be between 5 and 10 digits.");
    //   setLoading(false);
    //   return;
    // }
    if (formData.contactMode === "email" && formData.email.trim() === "") {
      toast.error("Please provide your email address.");
      setLoading(false);
      return;
    }
    try {
      const res = await fetch(`${API_BASE_URL}${APIENDPOINTS.CONTACT_STORE}`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify(formData),
      });

      if (!res.ok) {
        setLoading(false);
        // toast.error(`Error: ${res?.status}`);
        toast.error("Something went wrong. Please try again.");
        return;
      }
      const data = await res.json();
      toast.success("Thanks for submitting enquiry! We will reach you soon");
      setIsModalOpen(false);
      setFormData({
        fullName: "",
        email: "",
        enquiryType: "",
        company_name: "",
        job_title: "",
        group_size: "",
        country: "",
        countryCode: "",
        course: "",
        phone: "",
        deliveryMode: "",
        contactMode: "",
        message: "",
        agree: "false",
      });
    } catch (err) {
      toast.error("Submission failed. Try again later.");
      console.error(err);
    }
    setLoading(false);
  };

  return (
    <>
      <div
        className={`fixed top-1/3 left-0 z-50 transition-all duration-500  max-sm:hidden
      ${
        show
          ? "opacity-100 translate-x-0"
          : "opacity-0 -translate-x-5 pointer-events-none"
      }`}
      >
        <div className="flex flex-col gap-3 items-center px-1 py-2 bg-white ring-1 ring-gray-300 rounded-r-xl shadow-lg">
          {/* Phone */}
          <div className="relative group">
            <a
              href={`tel:${phoneNumber}`}
              className="p-2 w-10 h-10 rounded-full cursor-pointer bg-gray-100 flex items-center justify-center
                       transition-all duration-300 hover:bg-[#882CFB]"
            >
              <IoCallOutline className="text-gray-800 w-5 h-5 group-hover:text-white transition-colors duration-300" />
            </a>
            <div
              className="absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2 py-1 text-gray-900 bg-white
                       border border-gray-300 rounded-md opacity-0 scale-75 transition-all duration-300 
                       group-hover:opacity-100 group-hover:scale-100 shadow-md"
            >
              Phone
            </div>
          </div>

          {/* Email */}
          <div className="relative group">
            <a
              href={`mailto:${emailAddress}`}
              className="p-2 w-10 h-10 rounded-full cursor-pointer bg-gray-100 flex items-center justify-center
                       transition-all duration-300 hover:bg-[#882CFB]"
            >
              <TfiEmail className="text-gray-800 w-5 h-5 group-hover:text-white transition-colors duration-300" />
            </a>
            <div
              className="absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2 py-1 text-gray-900 bg-white
                       border border-gray-300 rounded-md opacity-0 scale-75 transition-all duration-300 
                       group-hover:opacity-100 group-hover:scale-100 shadow-md"
            >
              Email
            </div>
          </div>

          {/* Chat */}
          <div className="relative group">
            <a
              href={`https://wa.me/${whatsappNumber}`}
              target="_blank"
              rel="noopener noreferrer"
              className="p-2 w-10 h-10 rounded-full cursor-pointer bg-gray-100 flex items-center justify-center
                       transition-all duration-300 hover:bg-[#882CFB]"
            >
              <BsChatDots className="text-gray-800 w-5 h-5 group-hover:text-white transition-colors duration-300" />
            </a>
            <div
              className="absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2 py-1 text-gray-900 bg-white
                       border border-gray-300 rounded-md opacity-0 scale-75 transition-all duration-300 
                       group-hover:opacity-100 group-hover:scale-100 shadow-md"
            >
              Chat
            </div>
          </div>

          <div className="relative group">
            <button
              onClick={() => setIsModalOpen(true)}
              type="button"
              className="p-2 w-10 h-10 rounded-full cursor-pointer bg-gray-100 flex items-center justify-center
               transition-all duration-300 hover:bg-[#882CFB]"
            >
              <BsPatchQuestion className="text-gray-800 w-5 h-5 group-hover:text-white transition-colors duration-300" />
            </button>

            <div
              className="absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2 py-1 text-gray-900 bg-white
               border border-gray-300 rounded-md opacity-0 scale-75 transition-all duration-300 
               group-hover:opacity-100 group-hover:scale-100 shadow-md whitespace-nowrap"
            >
              Enquiry
            </div>
          </div>
        </div>
      </div>

      <Modal
        isOpen={isModalOpen}
        onClose={() => setIsModalOpen(false)}
        title="Create Enquiry"
        width="max-w-2xl"
        height="max-h-[95vh]"
        position="center"
      >
        <div className="">
          <form
            onSubmit={handleSubmit}
            className="max-w-4xl mx-auto bg-white space-y-2"
          >
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <input
                type="text"
                name="fullName"
                placeholder="Name*"
                value={formData.fullName}
                onChange={handleChange}
                className="form-input"
              />
              <input
                type="email"
                name="email"
                placeholder="Email address*"
                value={formData.email}
                onChange={handleChange}
                className="form-input"
              />
              <select
                name="enquiryType"
                value={formData.enquiryType}
                onChange={handleChange}
                className="form-input"
              >
                <option value="">Select For*</option>
                <option value="individual">Individual</option>
                <option value="corporate">Corporate</option>
              </select>
              {formData.enquiryType === "corporate" && (
                <input
                  type="text"
                  name="company_name"
                  placeholder="Company Name*"
                  value={formData.company_name}
                  onChange={handleChange}
                  className="form-input"
                />
              )}
              {formData.enquiryType === "corporate" && (
                <input
                  type="text"
                  name="job_title"
                  placeholder="Job Title*"
                  value={formData.job_title}
                  onChange={handleChange}
                  className="form-input"
                />
              )}

              {formData.enquiryType === "corporate" && (
                <select
                  name="group_size"
                  value={formData.group_size}
                  onChange={handleChange}
                  className="form-input"
                >
                  <option value="">Select Size of Group Training*</option>
                  <option value="1-10">1-10</option>
                  <option value="11-20">11-20</option>
                  <option value="21-30">21-30</option>
                  <option value="31-60">31-60</option>
                  <option value="61-100">61-100</option>
                  <option value="nolimit">No Limit</option>
                </select>
              )}
              <select
                name="deliveryMode"
                value={formData.deliveryMode}
                onChange={handleChange}
                className="form-input"
              >
                <option value="">Training Delivery Mode*</option>
                <option value="offline">Offline</option>
                <option value="online">Online</option>
              </select>
            </div>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <select
                name="country"
                value={formData.country}
                onChange={handleChange}
                className="form-input"
              >
                <option value="">Select Country*</option>
                {countryLists?.map((country) => (
                  <option key={country.name} value={country.id}>
                    {country.name}
                  </option>
                ))}
              </select>
              <div className="grid grid-cols-[27%_70%] gap-3">
                <select
                  name="countryCode"
                  value={formData?.countryCode || ""}
                  className="form-input cursor-not-allowed"
                  disabled
                >
                  <option value="">Code</option>
                  {countryLists.map((item, index) => (
                    <option key={index} value={item.phonecode}>
                      {item.phonecode}
                    </option>
                  ))}
                </select>
                <input
                  type="text"
                  name="phone"
                  placeholder="Contact Number"
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
              <select
                name="contactMode"
                value={formData.contactMode}
                onChange={handleChange}
                className="form-input"
              >
                <option value="">Your mode of contact*</option>
                <option value="email">Email</option>
                <option value="phone">Phone</option>
                <option value="both">Both</option>
              </select>
              <select
                name="course"
                value={formData.course}
                onChange={handleChange}
                className="form-input"
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
              value={formData.message}
              onChange={handleChange}
              className="form-input"
            ></textarea>
            <div className="grid grid-cols-1 md:grid-cols-[70%_30%] items-center w-full">
              <div className="flex items-start gap-2">
                <input
                  type="checkbox"
                  name="agree"
                  checked={formData.agree === "true"}
                  onChange={handleChange}
                  className="w-3 h-3 cursor-pointer mt-[3px]"
                />
                <label className="text-sm">
                  I agree to receive communications and accept{" "}
                  <Link
                    target="_blank"
                    href={{
                      pathname: `/term-and-condition`,
                    }}
                    className="font-semibold  hover:underline"
                  >
                    Terms and Conditions
                  </Link>
                </label>
              </div>
              <div className="flex justify-end items-end">
                <button
                  type="submit"
                  disabled={loading}
                  className={`bg-[#882CFB] hover:bg-[#4347ca] w-fit cursor-pointer flex gap-1 items-center justify-center text-sm text-white py-2 px-5 rounded-full ${
                    loading ? "opacity-60 cursor-not-allowed" : ""
                  }`}
                >
                  {loading ? "Sending..." : "Submit"}{" "}
                  <HiMiniArrowLongRight className="mt-[1px]" />
                </button>
              </div>
            </div>
          </form>
        </div>
      </Modal>
    </>
  );
}

export default GlobalTooltip;
