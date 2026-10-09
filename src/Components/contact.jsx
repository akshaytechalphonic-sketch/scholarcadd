import React, { useEffect, useMemo, useState } from "react";
import { useAuth } from "@/context/AuthContext";
import { API_BASE_URL, APIENDPOINTS } from "../../apiconfig";
import toast from "react-hot-toast";
import Link from "next/link";
import { HiMiniArrowLongRight } from "react-icons/hi2";

function testimonials() {
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
    country_id: "",
    countryCode: "",
    phone: "",
    course_id: "",
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
    if (name === "country_id") {
      const selected = countryLists.find((item) => item.id == value);
      if (selected) {
        setFormData((prev) => ({
          ...prev,
          countryCode: selected.phonecode,
        }));
      } else {
        // country not selected → reset phone code
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
    // Required fields common for Individual and Corporate
    let requiredFields = [
      "fullName",
      "email",
      "enquiryType",
      "country_id",
      "course_id",
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
      toast.success("Thanks for contacting us! We will reach you soon.");
      setFormData({
        fullName: "",
        email: "",
        enquiryType: "",
        company_name: "",
        job_title: "",
        group_size: "",
        country_id: "",
        countryCode: "",
        course_id: "",
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
      <section className="">
        {/* Second Column */}
        <div className="w-full bg-gray-900  md:p-4 shadow-lg space-y-6 max-sm:pt-3">
          <div className="">
            <form
              onSubmit={handleSubmit}
              className=" bg-gray-900 space-y-4"
            >
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                {/* Full Name */}
                <input
                  type="text"
                  name="fullName"
                  placeholder="Name*"
                  value={formData.fullName}
                  onChange={handleChange}
                  className="border border-gray-700 rounded-md p-2 w-full bg-gray-800 text-gray-100"
                />

                {/* Email */}
                <input
                  type="email"
                  name="email"
                  placeholder="Email address*"
                  value={formData.email}
                  onChange={handleChange}
                  className="border border-gray-700 rounded-md p-2 w-full bg-gray-800 text-gray-100"
                />

                {/* Enquiry Type */}
                <select
                  name="enquiryType"
                  value={formData.enquiryType}
                  onChange={handleChange}
                  className="border border-gray-700 rounded-md p-2 w-full bg-gray-800 text-gray-100"
                >
                  <option value="">Select For*</option>
                  <option value="individual">Individual</option>
                  <option value="corporate">Corporate</option>
                </select>

                {/* Corporate Only: Company Name */}
                {formData.enquiryType === "corporate" && (
                  <input
                    type="text"
                    name="company_name"
                    placeholder="Company Name*"
                    value={formData.company_name}
                    onChange={handleChange}
                    className="border border-gray-700 rounded-md p-2 w-full bg-gray-800 text-gray-100"
                  />
                )}

                {/* Corporate Only: Job Title */}
                {formData.enquiryType === "corporate" && (
                  <input
                    type="text"
                    name="job_title"
                    placeholder="Job Title*"
                    value={formData.job_title}
                    onChange={handleChange}
                    className="border border-gray-700 rounded-md p-2 w-full bg-gray-800 text-gray-100"
                  />
                )}

                {/* Corporate Only: Group Size */}
                {formData.enquiryType === "corporate" && (
                  <select
                    name="group_size"
                    value={formData.group_size}
                    onChange={handleChange}
                    className="border border-gray-700 rounded-md p-2 w-full bg-gray-800 text-gray-100"
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

                {/* Delivery Mode */}
                <select
                  name="deliveryMode"
                  value={formData.deliveryMode}
                  onChange={handleChange}
                  className="border border-gray-700 rounded-md p-2 w-full bg-gray-800 text-gray-100"
                >
                  <option value="">Training Delivery Mode*</option>
                  <option value="offline">Offline</option>
                  <option value="online">Online</option>
                </select>
              </div>

              {/* Country + Phone */}
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <select
                  name="country_id"
                  value={formData.country_id}
                  onChange={handleChange}
                  className="border border-gray-700 rounded-md p-2 w-full bg-gray-800 text-gray-100"
                >
                  <option value="">Select Country*</option>
                  {countryLists?.map((country, index) => (
                    <option key={index} value={country?.id}>
                      {country?.name}
                    </option>
                  ))}
                </select>

                {/* Phone with country code */}
                <div className="grid grid-cols-[27%_70%] gap-3">
                  <select
                    name="countryCode"
                    value={formData?.countryCode || ""}
                    className="border border-gray-700 rounded-md p-2 bg-gray-800 text-gray-100 cursor-not-allowed"
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
                    className="border border-gray-700 rounded-md p-2 bg-gray-800 text-gray-100"
                  />
                </div>

                {/* Mode of contact */}
                <select
                  name="contactMode"
                  value={formData.contactMode}
                  onChange={handleChange}
                  className="border border-gray-700 rounded-md p-2 w-full bg-gray-800 text-gray-100"
                >
                  <option value="">Your mode of contact*</option>
                  <option value="email">Email</option>
                  <option value="phone">Phone</option>
                  <option value="both">Both</option>
                </select>
                {/* Course */}
                <select
                  name="course_id"
                  value={formData.course_id}
                  onChange={handleChange}
                  className="border border-gray-700 rounded-md p-2 w-full bg-gray-800 text-gray-100"
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

              {/* Message */}
              <textarea
                name="message"
                rows="4"
                maxLength={500}
                placeholder="Enter Your Training Requirements*"
                value={formData.message}
                onChange={handleChange}
                className="border border-gray-700 rounded-md p-2 w-full bg-gray-800 text-gray-100"
              ></textarea>

              {/* Agree Checkbox */}
              <div className="flex items-start gap-2">
                <input
                  type="checkbox"
                  name="agree"
                  checked={formData.agree === "true"}
                  onChange={handleChange}
                  className="w-4 h-4 cursor-pointer mt-[3px]"
                />
                <label className="text-gray-200 text-sm">
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
              {/* Submit */}
              <button
                type="submit"
                disabled={loading}
                className={`bg-[#882CFB] border-2 border-white  hover:bg-[#4347ca] px-8 cursor-pointer w-full text-white py-3 rounded-full flex gap-2 items-center justify-center ${
                  loading ? "opacity-60 cursor-not-allowed" : ""
                }`}
              >
                {loading ? "Sending..." : "Submit"}
                <HiMiniArrowLongRight />
              </button>
            </form>
          </div>
        </div>
      </section>
    </>
  );
}

export default testimonials;
