import Footer from "@/Components/Footer";
import Navbar from "@/Components/Navbar";
import { FiEye } from "react-icons/fi";
import { RiDeleteBin5Line } from "react-icons/ri";
import React, { useEffect, useMemo, useState, useRef } from "react";
import { API_BASE_URL, APIENDPOINTS } from "../../apiconfig";
import { useAuth } from "@/context/AuthContext";
import toast from "react-hot-toast";
import FormModal from "@/Components/FormModal";
import Link from "next/link";
import { HiMiniArrowLongRight } from "react-icons/hi2";
import { HiOutlineArrowNarrowRight } from "react-icons/hi";
import { IoCheckmark } from "react-icons/io5";
import {
  FaChalkboardTeacher,
  FaVideo,
  FaUsers,
  FaLaptop,
  FaUserGraduate,
  FaStar,
} from "react-icons/fa";
function joinus() {
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

  const [citylist, setcitylist] = useState([]);
  useEffect(() => {
    if (!token) return;
    const fetchcity = async () => {
      try {
        const res = await fetch(`${API_BASE_URL}${APIENDPOINTS.CITY_LIST}`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            Authorization: `Bearer ${token}`,
          },
          body: JSON.stringify({
            country_id: active_country,
            state_id: active_state || null,
          }),
        });
        if (!res.ok) {
          console.log("HTTP error! Status:", res?.status);
          setcitylist([]);
          return;
        }
        const data = await res.json();
        setcitylist(data?.data || []);
      } catch (err) {
        console.error("Error  :", err);
      }
    };
    fetchcity();
  }, [token]);

  const [files, setFiles] = useState([]);
  const fileInputRef = useRef(null);
  const [loading, setLoading] = useState(false);

  const [formData, setFormData] = useState({
    full_name: "",
    email: "",
    phone: "",
    country_id: "",
    company_name: "",
    job_title: "",
    training_expertise: "",
    course_id: "",
    delivery_mode: "",
    ready_to_travel: null,
    preferred_option: null,
    linkedin_profile: "",
    agree: 0,
  });
  console.log("formData", formData);
  const MAX_FILE_SIZE = 2 * 1024 * 1024;
  const ALLOWED_TYPES = [
    "application/pdf",
    "application/msword",
    "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
  ];
  const validateFiles = (files) => {
    const validFiles = [];

    for (const file of files) {
      if (!ALLOWED_TYPES.includes(file.type)) {
        toast.error(
          `${file.name} is not allowed. Only PDF, DOC, DOCX files are allowed.`,
        );
        continue;
      }
      if (file.size > MAX_FILE_SIZE) {
        toast.error(`${file.name} exceeds the 2 MB size limit.`);
        continue;
      }
      validFiles.push(file);
    }
    return validFiles;
  };

  // File Handlers
  const handleDragOver = (e) => e.preventDefault();
  const handleDrop = (e) => {
    e.preventDefault();
    const droppedFile = e.dataTransfer.files[0];
    if (!droppedFile) return;
    const validatedFiles = validateFiles([droppedFile]);
    setFiles(validatedFiles);
  };

  const handleFileChange = (e) => {
    const selectedFile = e.target.files[0];
    if (!selectedFile) return;
    const validatedFiles = validateFiles([selectedFile]);
    // Replace existing file
    setFiles(validatedFiles);
  };

  const handleDelete = (index) =>
    setFiles((prev) => prev.filter((_, i) => i !== index));

  const handleView = (file) => window.open(URL.createObjectURL(file), "_blank");

  // Form Handlers
  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;
    setFormData({
      ...formData,
      [name]: type === "checkbox" ? (checked ? 1 : 0) : value,
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    // Required validation
    const requiredFields = [
      "full_name",
      "email",
      "country_id",
      "company_name",
      "job_title",
      // "training_expertise",
      "course_id",
      "delivery_mode",
      "linkedin_profile",
      // "ready_to_travel",
      // "preferred_option",
    ];
    const isValidLinkedInUrl = (url) => {
      try {
        const parsed = new URL(url);
        return parsed.hostname.endsWith("linkedin.com");
      } catch {
        return false;
      }
    };
    if (
      formData.linkedin_profile &&
      !isValidLinkedInUrl(formData.linkedin_profile)
    ) {
      toast.error("Please enter a valid LinkedIn URL.");
      setLoading(false);
      return;
    }
    for (let field of requiredFields) {
      if (!formData[field] || formData[field].trim() === "") {
        toast.error("Please fill out all required fields.");
        setLoading(false);
        return;
      }
    }
    // CV required validation
    if (!files || files.length === 0) {
      toast.error("Please upload your CV (PDF or DOCX, max 2 MB).");
      setLoading(false);
      return;
    }
    if (formData.agree !== 1) {
      toast.error("Please agree to the terms before submitting.");
      return;
    }
    setLoading(true);
    try {
      const data = new FormData();

      Object.keys(formData).forEach((key) => {
        data.append(key, formData[key]);
      });
      files.forEach((file, index) => {
        // console.log(`Selected file ${index + 1}:`, file);
        data.append("uploaded_files[]", file);
      });
      const res = await fetch(
        `${API_BASE_URL}${APIENDPOINTS.TEAM_APPLICATION_FORM}`,
        {
          method: "POST",
          headers: {
            Authorization: `Bearer ${token}`,
          },
          body: data,
        },
      );
      if (!res.ok) {
        // toast.error(`Error: ${res.status}`);
        toast.error("Something went wrong. Please try again.");
        return;
      }

      await res.json();
      toast.success("Thanks for contacting us! We will reach you soon.");
      setFormData({
        full_name: "",
        email: "",
        phone: "",
        country_id: "",
        company_name: "",
        job_title: "",
        // training_expertise: "",
        course_id: "",
        delivery_mode: "",
        ready_to_travel: null,
        preferred_option: null,
        linkedin_profile: "",
        agree: 0,
      });
      setFiles([]);
    } catch (err) {
      console.error(err);
      toast.error("Submission failed. Try again later.");
    } finally {
      setLoading(false);
    }
  };

  const joinRef = useRef(null);

  const scrollToSection = () => {
    joinRef.current?.scrollIntoView({ behavior: "smooth" });
  };

  const [activeIndex, setActiveIndex] = useState(0);

  const trainingOptions = [
    {
      id: 1,
      title: "Classroom Instruction",
      description:
        "In person, instructor-led training courses for both corporate teams and individuals. Real-time feedback, interactive discussions, practical exercises, and hands-on learning are all made possible by this mode. ",
      icon: FaChalkboardTeacher,
    },
    {
      id: 2,
      title: "Live Online Instruction ",
      description:
        "Interactive platforms are used to conduct virtual, trainer-led sessions. Perfect for connecting with geographically scattered students while keeping them interested through conversations, exercises, and tests. ",
      icon: FaVideo,
    },
    {
      id: 3,
      title: "Programs for Corporate Training",
      description:
        "Tailored training programs created to address certain company requirements. Trainers work with corporate clients and Scholaracad to create business-aligned, outcome-driven programs. ",
      icon: FaUsers,
    },
    {
      id: 4,
      title: "Blended Education",
      description:
        "A mix of live trainer-led sessions and self-paced online learning. This method uses interactive assistance and structured content to reinforce concepts. ",
      icon: FaLaptop,
    },
    {
      id: 5,
      title: "Mentoring and Coaching Sessions",
      description:
        "Coaching programs run by trainers that incorporate group discussions and individualized advice. Peer learning, real-world problem-solving, and expert insights all benefit participants. ",
      icon: FaUserGraduate,
    },
  ];
  const ActiveIcon = trainingOptions[activeIndex].icon;
  return (
    <>
      <Navbar />
      <main className="font-nunito bg-white ">
        <div className="joinus_bg max-sm:p-3">
          <section className="max-w-7xl mx-auto md:py-[150px]  ">
            <div className="flex flex-col justify-center md:items-center max-sm:mt-10">
              <h1 className="heading_blue">Scholaracad – Join as a Trainer</h1>
              <p className="mt-4 para md:max-w-[80%] m-auto text-justify">
                Are you enthusiastic about teaching and self-assured in your
                subject matter? Subject Matter Experts (SMEs), industry
                professionals, and seasoned teachers are invited to join
                Scholaracad Learning's worldwide training network. Scholaracad
                gives you the perfect platform to share your knowledge and have
                a significant influence if you have professional certificates,
                practical experience, and pertinent subject knowledge.
                <br />
                <br />
                You may train professionals in a variety of industries, advance
                your career, and become a part of a reputable learning ecosystem
                by becoming a trainer with Scholaracad Learning. Our trainer
                network is essential to providing top-notch, industry-relevant
                training programs across the globe.
              </p>
              <div className="mt-8 flex gap-4">
                <button
                  onClick={scrollToSection}
                  type="button"
                  className="relative group border-none bg-transparent p-0 cursor-pointer"
                >
                  <div className="btn_primary">
                    <span className="select-none">Join Us</span>
                    <HiOutlineArrowNarrowRight />
                  </div>
                </button>
              </div>
            </div>
          </section>
        </div>

        <section className="relative  max-w-7xl mx-auto py-5 max-sm:p-3">
          <div className="md:max-w-[60%]  m-auto">
            <h2 className=" heading">
              Get the right leverage for your Hard-Earned Expertise
            </h2>
          </div>
          <div className="flex justify-center items-center mt-10">
            <img
              src="/assets/landingpage/Earned_ expertise_bg.svg"
              alt=""
              className="w-[80%]"
            />
          </div>
        </section>

        <div className="md:max-w-7xl mx-auto max-sm:p-3 py-5    ">
          <div className="bg-[#F7F3FF] rounded-md border border-gray-300 p-5">
            <h2 className="heading ">Why Join Scholaracad as a Trainer</h2>
            <p className="para  mt-3 ">
              Scholaracad enables trainers to advance their careers while
              significantly advancing workforce transformation and skill
              development. We make sure that trainers may concentrate on
              providing excellence and developing long-term training careers in
              a supportive environment.
            </p>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Possibility of working with corporate clients and a worldwide
                  learner base
                </p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Possibility of working with corporate clients and a worldwide
                  learner base
                </p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Options for delivering training that are flexible (online,
                  classroom, corporate)
                </p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Regular training assignments determined by availability and
                  expertise
                </p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Professional development by exposure to many fields and
                  sectors
                </p>
              </div>
            </div>
          </div>
        </div>

        <section className="relative  max-sm:p-3 max-w-7xl mx-auto py-5 grid lg:grid-cols-2 gap-10 items-start">
          <div className="">
            <h2 className="heading">Who Can Join as a Trainer</h2>
            <p className="para my-4">
              Qualified professionals from a variety of fields who are ready to
              mentor students and share their knowledge are welcome at
              Scholaracad.
            </p>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Industry practitioners and corporate trainers
                </p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  SMEs (subject matter experts) having practical experience
                </p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Professionals and consultants with certifications
                </p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">Teachers and independent instructors</p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Mentors and coaches with specialized knowledge
                </p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Professionals who are enthusiastic in developing skills and
                  teaching
                </p>
              </div>
            </div>
          </div>

          <div>
            <img
              src="/assets/landingpage/Transform _individuals_bg.svg"
              alt=""
              className=" border-1 border-gray-200 rounded-md"
            />
          </div>
        </section>

        <div className=" max-sm:p-3">
          <section className="  max-w-7xl mx-auto py-5 grid lg:grid-cols-2 gap-10 items-start">
            <div>
              <img
                src="/assets/landingpage/Expand _your_training_img.svg"
                alt=""
              />
            </div>
            <div className="">
              <h2 className="heading">Key Benefits of Joining as a Trainer</h2>
              <p className="para my-4">
                You can broaden your professional network and provide
                high-impact learning opportunities by becoming a trainer at
                Scholaracad. You gain from well-organized courses, motivated
                students, and a cooperative training environment.
              </p>
              <div className="grid grid-cols-1  gap-5 mt-5">
                <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                  <IoCheckmark className="mt-1" />
                  <p className="para">
                    Increased credibility and visibility in the workplace
                  </p>
                </div>
                <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                  <IoCheckmark className="mt-1" />
                  <p className="para">
                    Possibility of influencing careers and organizational
                    development
                  </p>
                </div>
                <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                  <IoCheckmark className="mt-1" />
                  <p className="para">
                    Availability of a variety of training types and audiences
                  </p>
                </div>
                <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                  <IoCheckmark className="mt-1" />
                  <p className="para">
                    Long-term cooperation and frequent training
                    opportunities{" "}
                  </p>
                </div>
                <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                  <IoCheckmark className="mt-1" />
                  <p className="para">
                    Assistance with learner engagement and program coordination
                  </p>
                </div>
              </div>
            </div>
          </section>
        </div>
        <section className="tranning_delivery_bg  text-[#2E318D] max-sm:p-3">
          <div className="md:max-w-7xl mx-auto py-[200px] ">
            <div className="flex justify-center items-center flex-col ">
              <h2 className="heading text-center">Training Delivery Modes</h2>
            </div>
            <div className="grid grid-cols-1 md:grid-cols-2 my-3 ">
              <div className="">
                <div class="md:max-w-md mx-auto mt-10 max-sm:p-7">
                  <ul className="relative border">
                    {trainingOptions.map((item, index) => (
                      <li
                        key={item.id}
                        onClick={() => setActiveIndex(index)}
                        className={`relative border-b py-4 cursor-pointer transition
                                  ${
                                    activeIndex === index
                                      ? "bg-[#E4D3FF]"
                                      : "hover:bg-gray-50"
                                  }
                                `}
                      >
                        <span
                          className={`absolute -left-5 top-0 my-4 w-8 h-8 flex items-center justify-center 
                                text-white font-bold rounded
                                ${
                                  activeIndex === index
                                    ? "bg-[#882CFB]"
                                    : "bg-gray-400"
                                }
                              `}
                        >
                          {String(index + 1).padStart(2, "0")}
                        </span>

                        <span
                          className={`font-bold text-lg ml-10
                                    ${
                                      activeIndex === index
                                        ? "text-[#2E318D]"
                                        : "text-gray-600"
                                    }
                                  `}
                        >
                          {item.title}
                        </span>
                      </li>
                    ))}
                  </ul>
                </div>
              </div>

              <div className="flex flex-col justify-center items-center gap-3 p-10">
                <div className="w-[250px] h-[250px] rounded-full bg-white flex justify-center items-center shadow">
                  <ActiveIcon
                    className={`w-[100px] h-[100px] transition animate-pulse
                      ${
                        activeIndex !== null
                          ? "text-[#882CFB]"
                          : "text-gray-400"
                      }
                    `}
                  />
                </div>

                <p className="text-center font-bold md:max-w-[90%]">
                  {trainingOptions[activeIndex].description}
                </p>
              </div>
            </div>
          </div>
        </section>

        {/* Fourth Section */}
        <section ref={joinRef} className="bg-white max-sm:p-3 my-10">
          <div className="md:max-w-7xl mx-auto">
            <div className="w-full bg-gray-900  md:p-6  shadow-lg  rounded-lg ">
              <div className=" mx-auto">
                <div>
                  <div className="flex md:flex-row flex-col gap-2 justify-between items-center">
                    <h4 className="heading_white ">Join Our Growing Team</h4>
                    <div className="">
                      <img src="/assets/landingpage/googlerating.svg" alt="" />
                    </div>
                  </div>
                  <form
                    className="py-5 rounded-2xl shadow-md space-y-6 max-sm:p-3"
                    onSubmit={handleSubmit}
                  >
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                      <input
                        type="text"
                        name="full_name"
                        value={formData.full_name}
                        onChange={handleChange}
                        placeholder="Full Name*"
                        className="border border-gray-300 rounded-md p-2 w-full bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                      />
                      <input
                        type="email"
                        name="email"
                        value={formData.email}
                        onChange={handleChange}
                        placeholder="Email address*"
                        className="border border-gray-300 rounded-md p-2 w-full bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                      />
                      <input
                        type="tel"
                        name="phone"
                        value={formData.phone}
                        onChange={(e) => {
                          let value = e.target.value;
                          if (!/^[+\d]*$/.test(value)) return;
                          const digitsOnly = value.replace("+", "");
                          if (digitsOnly.length > 10) return;
                          handleChange(e);
                        }}
                        placeholder="Phone Number"
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
                          <option key={country.name} value={country.id}>
                            {country?.name}
                          </option>
                        ))}
                      </select>
                      <input
                        type="text"
                        name="company_name"
                        value={formData.company_name}
                        onChange={handleChange}
                        placeholder="Company Name*"
                        className="border border-gray-300 rounded-md p-2 w-full bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                      />
                      <input
                        type="text"
                        name="job_title"
                        value={formData.job_title}
                        onChange={handleChange}
                        placeholder="Job Title*"
                        className="border border-gray-300 rounded-md p-2 w-full bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                      />
                      {/* <select
                        name="training_expertise"
                        value={formData.training_expertise}
                        onChange={handleChange}
                        className="border border-gray-300 rounded-md p-2 w-full bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                      >
                        <option value="">Select Training Expertise*</option>
                        <option value="india">India</option>
                        <option value="usa">USA</option>
                        <option value="uk">UK</option>
                      </select> */}
                      <select
                        name="course_id"
                        value={formData.course_id}
                        onChange={handleChange}
                        className="border border-gray-300 rounded-md p-2 w-full bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                      >
                        <option value="">Select Course*</option>
                        {categories?.flatMap((category) =>
                          category?.cources?.map((course) => (
                            <option key={course?.course_id} value={course?.id}>
                              {course?.course_short_name}
                            </option>
                          )),
                        )}
                      </select>
                      <select
                        name="delivery_mode"
                        value={formData.delivery_mode}
                        onChange={handleChange}
                        className="border border-gray-300 rounded-md p-2 w-full bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                      >
                        <option value="">Select Training Delivery Mode*</option>
                        <option value="Online">Online</option>
                        <option value="offline">Offline</option>
                      </select>
                      <div className="flex flex-col gap-2">
                        {/* <label className="font-medium text-white">
                          Enter Your Linkedin Profile Link
                        </label> */}
                        <input
                          type="text"
                          name="linkedin_profile"
                          value={formData.linkedin_profile}
                          onChange={handleChange}
                          placeholder="LinkedIn URL*"
                          className="border border-gray-300 rounded-md p-2 w-full bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                      </div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                      {/* First Enquiry */}
                      <div className="flex flex-col">
                        <label
                          htmlFor="enquiryType1"
                          className="mb-2 font-medium text-gray-200"
                        >
                          Are You Ready To Travel?
                        </label>
                        <div className="grid grid-cols-2 bg-white p-2 rounded-md">
                          <div className="flex gap-2 items-center">
                            <input
                              type="radio"
                              name="ready_to_travel"
                              value="Yes"
                              checked={formData.ready_to_travel === "Yes"}
                              onChange={handleChange}
                              className="w-4 h-4 text-blue-500 border-gray-300 rounded-full "
                            />
                            <label
                              htmlFor="individual1"
                              className="font-medium text-gray-700"
                            >
                              Yes
                            </label>
                          </div>
                          <div className="flex gap-2 items-center">
                            <input
                              type="radio"
                              name="ready_to_travel"
                              value="No"
                              checked={formData.ready_to_travel === "No"}
                              onChange={handleChange}
                              className="w-4 h-4 text-blue-500 border-gray-300 rounded-full "
                            />
                            <label
                              htmlFor="corporate1"
                              className="font-medium text-gray-700"
                            >
                              No
                            </label>
                          </div>
                        </div>
                      </div>
                      <div className="flex flex-col">
                        <label
                          htmlFor="enquiryType1"
                          className="mb-2 font-medium text-gray-200"
                        >
                          Preferred option?
                        </label>
                        <div className="grid grid-cols-2 bg-white p-2 rounded-md">
                          <div className="flex gap-2 items-center">
                            <input
                              type="radio"
                              name="preferred_option"
                              value="Full Time"
                              checked={
                                formData.preferred_option === "Full Time"
                              }
                              onChange={handleChange}
                              className="w-4 h-4 text-blue-500 border-gray-300 rounded-full "
                            />
                            <label
                              htmlFor="individual1"
                              className="font-medium text-gray-700"
                            >
                              Full Time
                            </label>
                          </div>
                          <div className="flex gap-2 items-center">
                            <input
                              type="radio"
                              name="preferred_option"
                              value="Part Time"
                              checked={
                                formData.preferred_option === "Part Time"
                              }
                              onChange={handleChange}
                              className="w-4 h-4 text-blue-500 border-gray-300 rounded-full "
                            />
                            <label
                              htmlFor="corporate1"
                              className="font-medium text-gray-700"
                            >
                              Part Time
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                    {/* File Upload */}
                    <div className="rounded-md bg-gradient-to-b from-[#FFFFFF] to-[#F4EFFF] p-2">
                      <div
                        onDragOver={handleDragOver}
                        onDrop={handleDrop}
                        onClick={() => fileInputRef.current.click()}
                        className="w-full h-48 border-2 border-dashed border-gray-400 rounded-2xl flex flex-col justify-center items-center bg-white hover:bg-gray-50 transition cursor-pointer"
                      >
                        <p className="text-center">
                          Drag & Drop files here or{" "}
                          <span className="text-blue-600 underline">
                            Click to Upload CV
                          </span>
                        </p>
                        <span className="text-xs text-red-500 font-bold mt-1">
                          Only PDF and DOCX files allowed (Max 2 MB)
                        </span>
                        <input
                          type="file"
                          ref={fileInputRef}
                          accept=".pdf,.docx"
                          onChange={handleFileChange}
                          className="hidden"
                        />
                      </div>
                    </div>

                    {/* Selected Files */}
                    {files?.length > 0 && (
                      <ul className="space-y-2 text-white">
                        {files?.map((file, idx) => (
                          <li
                            key={idx}
                            className="flex justify-between items-center border p-2 rounded"
                          >
                            <div className="truncate">
                              <span>{file?.name}</span> (
                              {(file.size / 1024).toFixed(2)} KB)
                            </div>
                            <div className="flex gap-2">
                              {/* <button
                                type="button"
                                onClick={() => handleView(file)}
                              >
                                <FiEye />
                              </button> */}
                              <button
                                type="button"
                                onClick={() => handleDelete(idx)}
                              >
                                <RiDeleteBin5Line />
                              </button>
                            </div>
                          </li>
                        ))}
                      </ul>
                    )}

                    <div className="grid grid-cols-1 md:grid-cols-[80%_20%] gap-2 items-center w-full">
                      <div>
                        {/* Checkbox */}
                        <label className="flex items-center gap-2 text-white">
                          <input
                            type="checkbox"
                            name="agree"
                            checked={formData.agree === 1}
                            onChange={handleChange}
                          />
                          <span className="text-wrap">
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
                          </span>
                        </label>
                      </div>

                      <div className="flex justify-end items-end">
                        {/* Submit Button */}
                        <button
                          type="submit"
                          disabled={loading}
                          className={`w-full py-3 rounded-full flex gap-1 cursor-pointer items-center justify-center text-white ${
                            loading
                              ? "bg-gray-400 cursor-not-allowed"
                              : "bg-[#882CFB] border-2 border-white w-full hover:bg-[#4347ca]"
                          }`}
                        >
                          {loading ? "Submitting..." : "Submit"}
                          <HiMiniArrowLongRight />
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <br />
        </section>
        <hr className="text-gray-200" />
      </main>
      <Footer />
    </>
  );
}

export default joinus;

export async function getStaticProps() {
  return {
    props: {
      title: "ScholarAcad | Explore Our Individual Training Program",
      description:
        "Experience personalized learning with ScholarAcad’s Individual Training Program. Get one-on-one guidance to enhance skills, address gaps, and achieve your goals.",
      keywords:
        "Personalized training , One-on-one training, Individual training program, Customized training programs,Personalized learning courses,Skill development training, Tailored training sessions",
    },
  };
}
