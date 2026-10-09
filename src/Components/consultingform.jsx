import React, { useEffect, useState } from "react";
import { API_BASE_URL, APIENDPOINTS } from "../../apiconfig";
import { useAuth } from "@/context/AuthContext";
import toast from "react-hot-toast";
import { HiMiniArrowLongRight } from "react-icons/hi2";

function consultingform() {
  const { token } = useAuth();
  const { categories } = useAuth();
  const [loading, setLoading] = useState(false);

  const [Consultinglist, SetConsultinglist] = useState([]);
  // console.log("Consultinglistform----->", Consultinglist);
  useEffect(() => {
    if (!token) return;
    const Consultinglist = async () => {
      try {
        const res = await fetch(
          `${API_BASE_URL}${APIENDPOINTS.CONSULTING_LIST}`,
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              Accept: "application/json",
              Authorization: `Bearer ${token}`,
            },
          }
        );
        if (!res.ok) {
          console.log("HTTP error! Status:", res?.status);
          SetConsultinglist([]);
          return;
        }
        const data = await res.json();
        SetConsultinglist(data?.data || []);
      } catch (err) {
        console.error("Error Recent Blogs :", err);
      }
    };
    Consultinglist();
  }, [token]);

  const [formData, setFormData] = useState({
    fullName: "",
    phone: "",
    email: "",
    course: "",
    enquiryType: "individual",
    company_name: "",
    job_title: "",
    group_size: "",
    message: "",
  });

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({
      ...prev,
      [name]: value,
    }));
  };

  const handleRadioChange = (e) => {
    setFormData((prev) => ({
      ...prev,
      enquiryFor: e.target.value,
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (
      !formData.fullName ||
      // !formData.phone ||
      !formData.email ||
      !formData.message
    ) {
      toast.error("Please fill all required fields.");
      return;
    }
    if (formData.enquiryFor === "corporate") {
      if (
        !formData.company_name ||
        !formData.job_title ||
        !formData.group_size
      ) {
        toast.error("Please fill all corporate details.");
        return;
      }
    }
    setLoading(true);
    try {
      const res = await fetch(
        `${API_BASE_URL}${APIENDPOINTS.ADD_CONSULTATION_FORM}`,
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
        // toast.error(`Error: ${res.status}`);
        toast.error("Something went wrong. Please try again.");
        return;
      }
      await res.json();
      toast.success("Thanks for contacting us! We will reach you soon.");
      setFormData({
        fullName: "",
        phone: "",
        email: "",
        course: "",
        enquiryType: "individual",
        company_name: "",
        job_title: "",
        group_size: "",
        message: "",
      });
    } catch (err) {
      console.error(err);
      toast.error("Submission failed. Try again later.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <>
      <form className="max-w-4xl mx-auto p-2 space-y-4" onSubmit={handleSubmit}>
        {/* Full Name */}
        <input
          placeholder="Full Name*"
          type="text"
          name="fullName"
          value={formData.fullName}
          onChange={handleChange}
          className="border border-gray-300 rounded-md p-2 w-full bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />

        {/* Phone & Email */}
        <div className="grid grid-cols-1 gap-4">
          <input
            placeholder="Phone Number"
            type="text"
            name="phone"
            value={formData.phone}
            onChange={(e) => {
              let value = e.target.value;
              if (!/^[+\d]*$/.test(value)) return;
              const digitsOnly = value.replace("+", "");
              if (digitsOnly.length > 10) return;
              handleChange(e);
            }}
            className="border border-gray-300 rounded-md p-2 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
          <input
            placeholder="Email ID*"
            type="email"
            name="email"
            value={formData.email}
            onChange={handleChange}
            className="border border-gray-300 rounded-md p-2 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        {/* Course Dropdown */}
        <select
          name="course"
          value={formData.course}
          onChange={handleChange}
          className="border border-gray-300 rounded-md p-2 w-full bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <option value="">Select Consulting*</option>

          {Consultinglist?.map((list, index) => (
            <option key={index} value={list?.banner_Heading}>
              {list?.banner_Heading}
            </option>
          ))}
        </select>

        {/* Individual / Corporate Radio Buttons */}
        <div className="grid grid-cols-3 bg-white p-2 rounded-md">
          <label className="font-medium text-gray-500">Enquiry For*</label>
          {["individual", "corporate"].map((type) => (
            <div key={type} className="flex gap-2 items-center">
              <input
                type="radio"
                name="enquiryFor"
                value={type}
                checked={formData.enquiryFor === type}
                onChange={handleRadioChange}
              />
              <label className="font-medium text-gray-700">
                {type.charAt(0).toUpperCase() + type.slice(1)}
              </label>
            </div>
          ))}
        </div>

        {/* Conditional Corporate Fields */}
        {formData.enquiryFor === "corporate" && (
          <div className="flex flex-col gap-3">
            <input
              type="text"
              name="company_name"
              placeholder="Company Name*"
              value={formData.company_name}
              onChange={handleChange}
              className="border border-gray-300 rounded-md p-2 w-full bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <input
              type="text"
              name="job_title"
              placeholder="Job Title*"
              value={formData.job_title}
              onChange={handleChange}
              className="border border-gray-300 rounded-md p-2 w-full bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <select
              name="group_size"
              value={formData.group_size}
              onChange={handleChange}
              className="border border-gray-300 rounded-md p-2 w-full bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Select Size*</option>
              <option value="1-10">1-10</option>
              <option value="11-20">11-20</option>
              <option value="21-30">21-30</option>
              <option value="31-60">31-60</option>
              <option value="61-100">61-100</option>
              <option value="nolimit">No Limit</option>
            </select>
          </div>
        )}

        {/* Message */}
        <textarea
          placeholder="Message*"
          name="message"
          value={formData.message}
          onChange={handleChange}
          rows="4"
          maxLength={500}
          className="border border-gray-300 rounded-md p-2 w-full bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
        ></textarea>

        {/* Submit Button */}
        <button
          type="submit"
          disabled={loading}
          className={`bg-[#882CFB] border-2 cursor-pointer border-white  hover:bg-[#4347ca] px-8 text-white py-3 rounded-full w-full flex gap-2 items-center justify-center ${
            loading ? "opacity-50 cursor-not-allowed" : ""
          }`}
        >
          {loading ? "Submitting..." : "Submit"}
          <HiMiniArrowLongRight />
        </button>
      </form>
    </>
  );
}

export default consultingform;
