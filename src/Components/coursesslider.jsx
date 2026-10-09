import React, { useState } from "react";
import {
  FaClock,
  FaUsers,
  FaSearch,
  FaChevronLeft,
  FaChevronRight,
  FaArrowLeft,
} from "react-icons/fa";
import { FaEye } from "react-icons/fa";
import { GoDownload } from "react-icons/go";
import { RiEdit2Line } from "react-icons/ri";
import { RiRefreshLine } from "react-icons/ri";
const tabs = [
  "Popular Courses",
  "Certification Programs",
  "Skill-based Courses",
];
import Modal from "../Components/modal";

// Sample Data with tabType
const courses = [
  {
    id: 1,
    title: "Agile Scrum Foundation",
    provider: "EXIN",
    mode: "Live Virtual Classroom | Onsite Corporate",
    duration: "16 Hrs",
    enrolled: "4411",
    price: "₹ 26,500.00",
    img: "/assets/landingpage/courses/course2.png",
    tag: "Popular Course",
    tabType: 0, // Popular
  },
  {
    id: 2,
    title: "CSM® Certification",
    provider: "Scrum Alliance",
    mode: "Live Instructor-Led Classroom",
    duration: "16 Hrs",
    enrolled: "1715",
    price: "₹ 19,000.00",
    img: "/assets/landingpage/blog1_image.png",
    tag: "Popular Course",
    tabType: 0, // Popular
  },
  {
    id: 3,
    title: "PMP® Certification",
    provider: "PMI",
    mode: "Live Virtual Classroom | Onsite Corporate",
    duration: "32 Hrs",
    enrolled: "856",
    price: "₹ 14,500.00",
    img: "/assets/landingpage/courses/course1.jpg",
    tag: "Popular Course",
    tabType: 0, // Popular
  },
  {
    id: 4,
    title: "PMP® Certification",
    provider: "PMI",
    mode: "Live Virtual Classroom | Onsite Corporate",
    duration: "32 Hrs",
    enrolled: "856",
    price: "₹ 14,500.00",
    img: "/assets/landingpage/courses/course4.jpg",
    tag: "Popular Course",
    tabType: 0, // Popular
  },
  {
    id: 4,
    title: "Full-Stack Developer Program",
    provider: "Udemy",
    mode: "Online Bootcamp",
    duration: "50 Hrs",
    enrolled: "2200",
    price: "₹ 22,000.00",
    img: "/assets/landingpage/courses/course5.jpg",
    tag: "Certification",
    tabType: 1, // Certification Programs
  },
  {
    id: 5,
    title: "Digital Marketing Course",
    provider: "Coursera",
    mode: "Self-Paced | Online",
    duration: "30 Hrs",
    enrolled: "1200",
    price: "₹ 10,000.00",
    img: "/assets/landingpage/blog1_image.png",
    tag: "Certification",
    tabType: 1,
  },
  {
    id: 6,
    title: "Python Programming",
    provider: "CodeAcademy",
    mode: "Online | Self-Paced",
    duration: "20 Hrs",
    enrolled: "3400",
    price: "₹ 9,000.00",
    img: "/assets/landingpage/blog1_image.png",
    tag: "Skill Course",
    tabType: 2, // Skill-based
  },
  {
    id: 7,
    title: "Data Science Bootcamp",
    provider: "Coursera",
    mode: "Self-Paced | Online",
    duration: "40 Hrs",
    enrolled: "2000",
    price: "₹ 12,000.00",
    img: "/assets/landingpage/blog1_image.png",
    tag: "Skill Course",
    tabType: 2,
  },
  {
    id: 8,
    title: "Data Science Bootcamp",
    provider: "Coursera",
    mode: "Self-Paced | Online",
    duration: "40 Hrs",
    enrolled: "2000",
    price: "₹ 12,000.00",
    img: "/assets/landingpage/courses/course2.png",
    tag: "Skill Course",
    tabType: 0,
  },
  {
    id: 9,
    title: "Data Science Bootcamp",
    provider: "Coursera",
    mode: "Self-Paced | Online",
    duration: "40 Hrs",
    enrolled: "2000",
    price: "₹ 12,000.00",
    img: "/assets/landingpage/courses/course6.jpg",
    tag: "Skill Course",
    tabType: 0,
  },
];

export default function CoursesGrid() {
  const [openModal, setOpenModal] = useState(null);
  const [activeTab, setActiveTab] = useState(0);
  const [page, setPage] = useState(0);
  const perPage = 3;
  const [searchMode, setSearchMode] = useState(false);
  const [searchQuery, setSearchQuery] = useState("");
  // Filter courses based on active tab
  const filteredCourses = courses.filter((c) => c.tabType === activeTab);
  // Max pages for selected tab
  const maxPage = Math.ceil(filteredCourses.length / perPage);
  const nextPage = () => {
    if (page < maxPage - 1) setPage(page + 1);
  };
  const prevPage = () => {
    if (page > 0) setPage(page - 1);
  };

  // Register form validations
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
    <div className="md:max-w-7xl mx-auto w-full  mb-8">
      {/* Header */}
      <div className="flex items-center justify-between mb-4">
        <h2 className="text-[30px] md:text-[42px]  font-extrabold leading-tight ">
          Explore Trending Courses
        </h2>
      </div>
      {/* Search Mode */}
      {searchMode ? (
        <div className="flex justify-between items-center gap-4 mb-2">
          <button
            onClick={() => setSearchMode(false)}
            className="px-3 py-1 cursor-pointer border border-[#2E318D] text-[#2E318D] rounded-full hover:bg-[#2E318D] hover:text-white transition flex items-center gap-2"
          >
            <FaArrowLeft />
            Back to All Courses
          </button>
          <div className="flex gap-2 w-full max-w-md">
            <input
              type="text"
              placeholder="Search courses..."
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
              className="flex-1 px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none "
            />
            <button
              title="Search"
              className="px-3 py-1  bg-[#2E318D] cursor-pointer text-white rounded-r-md hover:bg-blue-700 flex items-center gap-2"
            >
              <FaSearch />
            </button>
          </div>
        </div>
      ) : (
        <>
          <div className="flex justify-between md:flex-row flex-col items-center">
            {/* Tabs */}
            <div className="flex max-sm:gap-2 md:space-x-4 flex-wrap  bg-white border border-gray-200 rounded-md p-2">
              {tabs.map((tab, i) => (
                <button
                  key={i}
                  onClick={() => {
                    setActiveTab(i);
                    setPage(0);
                  }}
                  className={`px-4 py-1 rounded-full max-sm:w-full border transition cursor-pointer ${
                    activeTab === i
                      ? "bg-[#E9F7FF] text-[#2E318D] border-[#2E318D]"
                      : "bg-white text-gray-700 border-gray-300 hover:bg-gray-100"
                  }`}
                >
                  {tab}
                </button>
              ))}
            </div>

            {/* Pagination Buttons */}
            <div className="flex justify-end space-x-3 max-sm:py-3">
              <button
                title="Search"
                onClick={() => setSearchMode(true)}
                className="flex items-center gap-2 px-3 py-1 max-sm:text-xs cursor-pointer  border border-[#2E318D] text-[#2E318D] rounded-full hover:bg-[#2E318D] hover:text-white transition"
              >
                <FaSearch /> Search
              </button>
              <button
                title="Previous"
                onClick={prevPage}
                disabled={page === 0}
                className={`md:h-[40px] md:w-[40px] h-[30px] w-[30px] flex items-center justify-center cursor-pointer rounded-full ${
                  page === 0
                    ? "bg-gray-300 text-gray-500 cursor-not-allowed"
                    : "bg-[#2E318D] text-white hover:bg-blue-700"
                }`}
              >
                <FaChevronLeft />
              </button>
              <button
                title="Next"
                onClick={nextPage}
                disabled={page >= maxPage - 1}
                className={`md:h-[40px] md:w-[40px] h-[30px] w-[30px] flex items-center justify-center cursor-pointer rounded-full ${
                  page >= maxPage - 1
                    ? "bg-gray-300 text-gray-500 cursor-not-allowed"
                    : "bg-[#2E318D] text-white hover:bg-blue-700"
                }`}
              >
                <FaChevronRight />
              </button>
            </div>
          </div>
        </>
      )}
      {/* Smooth Slider */}
      <div className="overflow-hidden relative">
        <div
          className="flex transition-transform duration-1200 ease-in-out"
          style={{ transform: `translateX(-${page * 100}%)` }}
        >
          {filteredCourses.map((course) => (
            <div
              key={course.id}
              className="w-full sm:w-1/2 lg:w-1/3 flex-shrink-0 p-2"
            >
              <div className="bg-white relative rounded-2xl p-2 shadow-md overflow-hidden hover:shadow-lg transition h-full">
                <img
                  src={course.img}
                  alt={course.title}
                  className="w-full h-48 object-cover rounded-md"
                />
                <div className="p-4">
                  {course.tag && (
                    <span className="bg-yellow-500 text-white text-xs px-3 py-1 rounded-br-xl mb-2 inline-block absolute top-0 left-0">
                      {course.tag}
                    </span>
                  )}
                  <div className="flex items-center gap-4 text-sm text-gray-500 mb-3">
                    <span className="flex items-center gap-1">
                      <FaClock /> {course.duration}
                    </span>
                    <span className="flex items-center gap-1">
                      <FaUsers /> {course.enrolled}
                    </span>
                  </div>
                  <h4 className="text-sm text-gray-500">{course.provider}</h4>
                  <p className="text-xs text-gray-400 mb-2">{course.mode}</p>
                  <h3 className="font-semibold text-lg mb-3">{course.title}</h3>
                  <p className="text-green-600 font-bold mb-4">
                    Start from {course.price}
                  </p>
                  <div className="flex md:flex-row flex-col gap-3">
                    <button className="flex-1 px-3 py-2 border rounded-md hover:bg-gray-100 flex items-center justify-center gap-2">
                      <FaEye className="text-[#2E318D]" />
                      View Details
                    </button>
                    <button
                      onClick={() => setOpenModal("register")}
                      className="flex-1 px-3 py-2 bg-[#2E318D] text-white cursor-pointer rounded-md hover:bg-blue-700 flex items-center justify-center gap-2"
                    >
                      <GoDownload />
                      Curriculum
                    </button>
                  </div>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>

      <div className=" flex items-center justify-center bg-gray-100">
        {/* Open Modal Button for Register */}
        {/* <button
          onClick={() => setOpenModal("register")}
          className="px-5 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700"
        >
          Open Modal Register
        </button> */}
        <Modal
          isOpen={openModal === "register"}
          onClose={() => setOpenModal(null)}
          title="Request For Registration"
          width="max-w-2xl"
          position="center"
        >
          <form className="space-y-5 mb-8" onSubmit={handleSubmit} noValidate>
            {/* Header */}
            <div>
              <h2 className="text-xl font-bold text-gray-800">Register Now</h2>
              <p className="text-gray-500 text-sm">
                Fill in the details below to register quickly.
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
    </div>
  );
}
