import Footer from "@/Components/Footer";
import Navbar from "@/Components/Navbar";
import { useState } from "react";
import { AiOutlineBook, AiOutlineClockCircle } from "react-icons/ai";
import { FaStar } from "react-icons/fa";
import { FaSquareInstagram } from "react-icons/fa6";
import { FcGoogle } from "react-icons/fc";
import { FiSearch } from "react-icons/fi";
import { GoArrowUpRight } from "react-icons/go";
import { IoIosStar } from "react-icons/io";
import { IoLogoFacebook } from "react-icons/io5";
import { RiGraduationCapFill } from "react-icons/ri";

function Course() {
  return (
    <>
      <Navbar />
      <section className="font-nunito bg-white  ">
        {/* Second Section */}
        <section className="max-sm:p-3">
          <div className="max-w-7xl mx-auto py-10 ">
            <div className="text-center my-6">
              <h1 className="text-[30px] md:text-[42px]  font-extrabold leading-tight">
                Wide range of Training and Certification Courses
              </h1>
              <div className=" mt-3">
                <h1 className="font-semibold text-md text-[#535353]  ">
                  Choose any course across categories
                </h1>
              </div>
            </div>

            <section class="relative  mt-12">
              {/* <div class="absolute left-1/2 transform -translate-x-1/2 top-0 bottom-0 w-1 border-l-2 border-dashed border-gray-300"></div> */}
              <div class="space-y-2 max-sm:space-y-10 max-w-7xl mx-auto relative">
                <div class="flex flex-col md:flex-row items-center md:justify-start relative">
                  <div class="md:w-1/2 flex justify-center md:justify-end pr-10 relative">
                    {/* <div class="hidden md:block absolute top-1/2 right-[-70px] w-32 h-32 border-t-2 border-r-2 border-dashed border-gray-300 rounded-tr-full"></div> */}
                    <div class="bg-gradient-to-br from-[#EBFBFF] to-[#C9F4FF] shadow-xl rounded-xl p-6 max-w-xs transform rotate-[3deg] hover:rotate-0 transition-all duration-300 relative z-10">
                      <h2 class="text-5xl font-bold text-[#29A6DD]">01</h2>
                      <h3 class="text-xl font-semibold text-gray-700 mt-1">
                        Project Management
                      </h3>
                      <div className=" gap-2 mt-2">
                        <RiGraduationCapFill className="text-[#29A6DD] text-2xl rotate-340 ml-[-10px] " />
                        <h1 className="font-bold text-xl">2 Courses</h1>
                      </div>
                      <p class="text-sm text-gray-600 mt-2 leading-relaxed p-2 bg-white rounded-xl">
                        The Program Management course category is designed to
                        equip professionals with the skills to manage multiple
                        related projects as a unified program that aligns with
                        strategic business goals. These courses…
                      </p>
                    </div>
                  </div>
                </div>

                <div class="flex flex-col md:flex-row items-center md:justify-end relative">
                  <div class="md:w-1/2 flex justify-center md:justify-start pl-10 relative">
                    <div class="hidden md:block absolute top-[-96px] left-[-70px] w-32 h-32 border-b-2 border-l-2 border-dashed border-gray-300 rounded-bl-full"></div>
                    {/* <div class="hidden md:block absolute top-1/2 left-[-70px] w-32 h-32 border-t-2 border-l-2 border-dashed border-gray-300 rounded-tl-full"></div> */}

                    <div class="bg-gradient-to-br from-[#FFF6E9] to-[#FFD0AF] shadow-xl rounded-xl p-6 max-w-xs transform rotate-[-3deg] hover:rotate-0 transition-all duration-300 relative z-10">
                      <h2 class="text-5xl font-bold text-[#E88E4E]">02</h2>
                      <h3 class="text-xl font-semibold text-gray-700 mt-1">
                        SAFe (Scaled Agile Framework)
                      </h3>
                      <div className=" gap-2 mt-2">
                        <RiGraduationCapFill className="text-[#29A6DD] text-2xl rotate-340 ml-[-10px] " />
                        <h1 className="font-bold text-xl">2 Courses</h1>
                      </div>
                      <p class="text-sm text-gray-600 mt-2 leading-relaxed p-2 bg-white rounded-xl">
                        The Program Management course category is designed to
                        equip professionals with the skills to manage multiple
                        related projects as a unified program that aligns with
                        strategic business goals. These courses…
                      </p>
                    </div>
                  </div>
                </div>

                <div class="flex flex-col md:flex-row items-center md:justify-start relative">
                  <div class="md:w-1/2 flex justify-center md:justify-end pr-10 relative">
                    <div class="hidden md:block absolute top-[-96px] right-[-70px] w-32 h-32 border-b-2 border-r-2 border-dashed border-gray-300 rounded-br-full"></div>
                    {/* <div class="hidden md:block absolute top-1/2 right-[-70px] w-32 h-32 border-t-2 border-r-2 border-dashed border-gray-300 rounded-tr-full"></div> */}

                    <div class="bg-gradient-to-br from-[#FFF0F0] to-[#FF9AA2] shadow-xl rounded-xl p-6 max-w-xs transform rotate-[3deg] hover:rotate-0 transition-all duration-300 relative z-10">
                      <h2 class="text-5xl font-bold text-[#FF8790]">03</h2>
                      <h3 class="text-xl font-semibold text-gray-700 mt-1">
                        Project Management
                      </h3>
                      <div className=" gap-2 mt-2">
                        <RiGraduationCapFill className="text-[#29A6DD] text-2xl rotate-340 ml-[-10px] " />
                        <h1 className="font-bold text-xl">2 Courses</h1>
                      </div>
                      <p class="text-sm text-gray-600 mt-2 leading-relaxed p-2 bg-white rounded-xl">
                        The Program Management course category is designed to
                        equip professionals with the skills to manage multiple
                        related projects as a unified program that aligns with
                        strategic business goals. These courses…
                      </p>
                    </div>
                  </div>
                </div>

                <div class="flex flex-col md:flex-row items-center md:justify-end relative">
                  <div class="md:w-1/2 flex justify-center md:justify-start pl-10 relative">
                    <div class="hidden md:block absolute top-[-96px] left-[-70px] w-32 h-32 border-b-2 border-l-2 border-dashed border-gray-300 rounded-bl-full"></div>
                    {/* <div class="hidden md:block absolute top-1/2 left-[-70px] w-32 h-32 border-t-2 border-l-2 border-dashed border-gray-300 rounded-tl-full"></div> */}

                    <div class="bg-gradient-to-br from-[#EDFFF8] to-[#B5EAD6] shadow-xl rounded-xl p-6 max-w-xs transform rotate-[-3deg] hover:rotate-0 transition-all duration-300 relative z-10">
                      <h2 class="text-5xl font-bold text-[#66C29F]">04</h2>
                      <h3 class="text-xl font-semibold text-gray-700 mt-1">
                        Project Management
                      </h3>
                      <div className=" gap-2 mt-2">
                        <RiGraduationCapFill className="text-[#29A6DD] text-2xl rotate-340 ml-[-10px] " />
                        <h1 className="font-bold text-xl">2 Courses</h1>
                      </div>
                      <p class="text-sm text-gray-600 mt-2 leading-relaxed p-2 bg-white rounded-xl">
                        The Program Management course category is designed to
                        equip professionals with the skills to manage multiple
                        related projects as a unified program that aligns with
                        strategic business goals. These courses…
                      </p>
                    </div>
                  </div>
                </div>

                <div class="flex flex-col md:flex-row items-center md:justify-start relative">
                  <div class="md:w-1/2 flex justify-center md:justify-end pr-10 relative">
                    <div class="hidden md:block absolute top-[-96px] right-[-70px] w-32 h-32 border-b-2 border-r-2 border-dashed border-gray-300 rounded-br-full"></div>
                    {/* <div class="hidden md:block absolute top-1/2 right-[-70px] w-32 h-32 border-t-2 border-r-2 border-dashed border-gray-300 rounded-tr-full"></div> */}

                    <div class="bg-gradient-to-br from-[#F5F7FF] to-[#C7CEEA] shadow-xl rounded-xl p-6 max-w-xs transform rotate-[3deg] hover:rotate-0 transition-all duration-300 relative z-10">
                      <h2 class="text-5xl font-bold text-[#5559CF]">05</h2>
                      <h3 class="text-xl font-semibold text-gray-700 mt-1">
                        Project Management
                      </h3>
                      <div className=" gap-2 mt-2">
                        <RiGraduationCapFill className="text-[#29A6DD] text-2xl rotate-340 ml-[-10px] " />
                        <h1 className="font-bold text-xl">2 Courses</h1>
                      </div>
                      <p class="text-sm text-gray-600 mt-2 leading-relaxed p-2 bg-white rounded-xl">
                        The Program Management course category is designed to
                        equip professionals with the skills to manage multiple
                        related projects as a unified program that aligns with
                        strategic business goals. These courses…
                      </p>
                    </div>
                  </div>
                </div>

                <div class="flex flex-col md:flex-row items-center md:justify-end relative">
                  <div class="md:w-1/2 flex justify-center md:justify-start pl-10 relative">
                    <div class="hidden md:block absolute top-[-96px] left-[-70px] w-32 h-32 border-b-2 border-l-2 border-dashed border-gray-300 rounded-bl-full"></div>

                    <div class="bg-gradient-to-br from-[#F9FFEF] to-[#A9C976] shadow-xl rounded-xl p-6 max-w-xs transform rotate-[-3deg] hover:rotate-0 transition-all duration-300 relative z-10">
                      <h2 class="text-5xl font-bold text-[#6A9C1C]">06</h2>
                      <h3 class="text-xl font-semibold text-gray-700 mt-1">
                        Project Management
                      </h3>
                      <div className=" gap-2 mt-2">
                        <RiGraduationCapFill className="text-[#29A6DD] text-2xl rotate-340 ml-[-10px] " />
                        <h1 className="font-bold text-xl">2 Courses</h1>
                      </div>
                      <p class="text-sm text-gray-600 mt-2 leading-relaxed p-2 bg-white rounded-xl">
                        The Program Management course category is designed to
                        equip professionals with the skills to manage multiple
                        related projects as a unified program that aligns with
                        strategic business goals. These courses…
                      </p>
                    </div>
                  </div>
                </div>

                <div class="flex flex-col md:flex-row items-center md:justify-start relative">
                  <div class="md:w-1/2 flex justify-center md:justify-end pr-10 relative">
                    <div class="hidden md:block absolute top-[-96px] right-[-70px] w-32 h-32 border-b-2 border-r-2 border-dashed border-gray-300 rounded-br-full"></div>
                    {/* <div class="hidden md:block absolute top-1/2 right-[-70px] w-32 h-32 border-t-2 border-r-2 border-dashed border-gray-300 rounded-tr-full"></div> */}
                    <div class="bg-gradient-to-br from-[#FDEFFF] to-[#FFB4EC] shadow-xl rounded-xl p-6 max-w-xs transform rotate-[3deg] hover:rotate-0 transition-all duration-300 relative z-10">
                      <h2 class="text-5xl font-bold text-[#FB62F9]">07</h2>
                      <h3 class="text-xl font-semibold text-gray-700 mt-1">
                        Project Management
                      </h3>
                      <div className=" gap-2 mt-2">
                        <RiGraduationCapFill className="text-[#29A6DD] text-2xl rotate-340 ml-[-10px] " />
                        <h1 className="font-bold text-xl">2 Courses</h1>
                      </div>
                      <p class="text-sm text-gray-600 mt-2 leading-relaxed p-2 bg-white rounded-xl">
                        The Program Management course category is designed to
                        equip professionals with the skills to manage multiple
                        related projects as a unified program that aligns with
                        strategic business goals. These courses…
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </section>
        <div className="max-sm:p-3">
          <section className="relative  max-w-7xl mx-auto py-12 grid lg:grid-cols-2 gap-10 items-start">
            <div className="mt-3">
              <h1 className=" text-[22px] md:text-[35px]  font-extrabold leading-tight">
                Get the Scholaracad Advantage
              </h1>
            </div>
            <div>
              <img
                src="/assets/landingpage/get the_scholaracad_advantage_img.svg"
                alt=""
              />
            </div>
          </section>
        </div>
        <div className="bg-[#F7F3FF] mb-10 max-sm:p-3">
          <section className="relative  max-w-7xl mx-auto py-12 grid lg:grid-cols-2 gap-10 items-start">
            <div>
              <img
                src="/assets/landingpage/Corporate_ enterprice_training.svg"
                alt=""
              />
            </div>
            <div className="mt-3">
              <h1 className=" text-[22px] md:text-[35px]  font-extrabold leading-tight">
                Your Knowledge Partner for Professional Growth
              </h1>
              <p className=" text-[20px] my-4">
                A leading player in the training and certifications space,
                Scholaracad has transformed the lives of thousands of IT and
                business professionals, by helping them upgrade their skills. In
                response to the changing industry landscape, Scholaracad is now
                offering management and soft skills training too. Know More
              </p>
              <div className="mt-8 flex gap-4">
                <button class="relative group border-none bg-transparent p-0  cursor-pointer  ">
                  <div class="relative flex items-center justify-between py-2 px-5 border-2 border-white  text-white rounded-full transform -translate-y-1 bg-[#2E318D] gap-3 transition duration-[600ms] ease-[cubic-bezier(0.3,0.7,0.4,1)] group-hover:-translate-y-1.5 group-hover:duration-[250ms] group-active:-translate-y-0.5 brightness-100 group-hover:brightness-110 ">
                    <span class="select-none">Contact Advisor</span>
                    <svg
                      viewBox="0 0 20 20"
                      fill="currentColor"
                      class="w-5 h-5 ml-2 -mr-1 transition duration-250 group-hover:translate-x-1"
                    >
                      <path
                        clipRule="evenodd"
                        d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                        fillRule="evenodd"
                      ></path>
                    </svg>
                  </div>
                </button>
              </div>
            </div>
          </section>
        </div>
        <section className="bg-gradient-to-b from-[#FFFFFF] to-[#F4EFFF] max-sm:p-3">
          <div className="md:max-w-7xl mx-auto  mb-10">
            <div className="w-full bg-gray-900 text-white md:p-6  shadow-lg  rounded-lg ">
              <div className=" mx-auto">
                <div>
                  <form className="  p-8 rounded-2xl shadow-md space-y-6">
                    <div className="flex md:flex-row flex-col gap-2 justify-between items-center">
                      <h4 className="text-[25px] md:text-[35px] text-start  text-white ">
                        Schedule an appointment
                      </h4>
                      <div className="">
                        <img
                          src="/assets/landingpage/googlerating.svg"
                          alt=""
                        />
                      </div>
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                      {/* Full Name */}
                      <div className="flex flex-col">
                        <input
                          type="text"
                          id="fullName"
                          placeholder="Name"
                          className="border border-gray-300 rounded-md p-2 w-full bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                      </div>
                      <div className="flex flex-col">
                        <input
                          type="email"
                          id="email"
                          placeholder="Email address"
                          className="border border-gray-300 rounded-md p-2 bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                      </div>
                      <div className="flex flex-col">
                        <input
                          type="Phone"
                          id="Phone"
                          placeholder="Phone Number"
                          className="border border-gray-300 rounded-md p-2 bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                      </div>
                      <div className="flex flex-col">
                        <select
                          id="country"
                          className="border border-gray-300 rounded-md p-2 w-full bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                          <option value="">Select Country</option>
                          <option value="india">India</option>
                          <option value="usa">USA</option>
                          <option value="uk">UK</option>
                        </select>
                      </div>
                      <div className="flex flex-col">
                        <select
                          id="country"
                          className="border border-gray-300 rounded-md p-2 w-full bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                          <option value="">Select Course</option>
                          <option value="india">India</option>
                          <option value="usa">USA</option>
                          <option value="uk">UK</option>
                        </select>
                      </div>
                      <div className="flex flex-col">
                        <select
                          id="country"
                          className="border border-gray-300 rounded-md p-2 w-full bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                          <option value="">Select Country</option>
                          <option value="india">India</option>
                          <option value="usa">USA</option>
                          <option value="uk">UK</option>
                        </select>
                      </div>
                    </div>

                    {/* Message */}
                    <div className="flex flex-col">
                      <textarea
                        id="message"
                        rows="4"
                        placeholder="Enter Your Training Requirements"
                        className="border border-gray-300 rounded-md p-2 w-full bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                      ></textarea>
                    </div>

                    {/* Checkbox */}
                    <div className="flex items-start gap-2">
                      <input
                        type="checkbox"
                        id="agree"
                        className="w-4 h-4 cursor-pointer text-blue-600 border-gray-400 rounded focus:ring-2 focus:ring-blue-500"
                      />
                      <label htmlFor="agree" className="text-white text-sm">
                        I agree to receive communication on newsletters,
                        discount, offers, updates, events, promotions, etc. By
                        clicking "Submit", you agree to our Terms of Conditions,
                        Privacy Policy.
                      </label>
                    </div>

                    {/* Submit Button */}
                    <div className="flex justify-end">
                      <button
                        type="submit"
                        className="bg-[#29A6DD] hover:bg-blue-700 px-8 w-full text-white py-3 rounded-full cursor-pointer transition-colors duration-200"
                      >
                        Send message
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <br />
        </section>

        {/* Testimonials */}
        <section className="testimonials_section max-sm:p-3">
          <div className="relative max-w-7xl mx-auto py-10 ">
            <div className="text-center">
              <div className="flex justify-center items-center flex-col mt-2">
                <div>
                  <RiGraduationCapFill className="text-[#29A6DD] text-4xl rotate-350 ml-[-15px] mb-[-5px] " />
                  <h1 className="font-bold text-xl text-[#2E318D] ">
                    Testimonials
                  </h1>
                </div>
              </div>
              <h1 className=" text-[30px] md:text-[42px]  font-extrabold leading-tight">
                Customer Speak About Scholaracad
              </h1>
              <div className="flex justify-center items-center md:gap-10 gap-4 my-4">
                <img
                  src="/assets/landingpage/blog1_image.png"
                  className="md:w-[70px] md:h-[70px] w-[50px] h-[50px] rounded-full transition-all duration-300 transform hover:scale-110 hover:border-4 hover:border-[#2E318D]"
                  alt=""
                />
                <img
                  src="/assets/landingpage/blog1_image.png"
                  className="md:w-[70px] md:h-[70px] w-[50px] h-[50px] rounded-full transition-all duration-300 transform hover:scale-110 hover:border-4 hover:border-[#2E318D]"
                  alt=""
                />
                <img
                  src="/assets/landingpage/blog1_image.png"
                  className="md:w-[100px] md:h-[100px] w-[70px] h-[70px] rounded-full transition-all duration-300 transform hover:scale-110 hover:border-4 hover:border-[#2E318D]"
                  alt=""
                />
                <img
                  src="/assets/landingpage/blog1_image.png"
                  className="md:w-[70px] md:h-[70px] w-[50px] h-[50px] rounded-full transition-all duration-300 transform hover:scale-110 hover:border-4 hover:border-[#2E318D]"
                  alt=""
                />
                <img
                  src="/assets/landingpage/blog1_image.png"
                  className="md:w-[70px] md:h-[70px] w-[50px] h-[50px] rounded-full transition-all duration-300 transform hover:scale-110 hover:border-4 hover:border-[#2E318D]"
                  alt=""
                />
              </div>

              <div className="flex flex-col gap-2 justify-center items-center">
                <h1 className="text-[25px] font-bold text-[#2E318D]">
                  Hilary Oise
                </h1>
                <p>Lecturer, Oxford University</p>
                <div className="flex gap-2">
                  <IoIosStar className="text-[#E4B308] text-2xl" />
                  <IoIosStar className="text-[#E4B308] text-2xl" />
                  <IoIosStar className="text-[#E4B308] text-2xl" />
                  <IoIosStar className="text-[#E4B308] text-2xl" />
                  <IoIosStar className="text-[#E4B308] text-2xl" />
                </div>
              </div>
              <p className="mt-6 text-lg text-gray-600  text-center">
                “At Scholaracad, we're reshaping the learning experience by
                seamlessly blending expertise with innovation. What sets us
                apart is our commitment to more than just plugging knowledge
                gaps – we're dedicated to unlocking your.”
              </p>
            </div>
          </div>
        </section>

        <section className="relative max-w-7xl mx-auto py-5 grid lg:grid-cols-2 gap-10 max-sm:p-3">
          <div className="">
            <h1 className=" text-[30px] md:text-[42px]  font-extrabold leading-tight">
              Trusted by Leading Institutions & Companies
            </h1>
            <p className="mt-4 text-lg text-gray-600 max-w-lg">
              Thousands of learners worldwide trust us to deliver high-quality
              professional education.
            </p>
            <p className=" text-lg text-gray-500 max-w-lg mt-6">
              Rated by learners
            </p>
            <div className="flex items-center gap-2 mt-2">
              <FaStar className="text-[#E4B308]" />
              <h1 className="font-bold text-xl">4.8/5</h1>
              <p className="text-lg text-gray-500  pl-3">12,500+ Reviews</p>
            </div>
          </div>
          <div className="grid grid-cols-2 md:grid-cols-3 gap-4">
            {[
              "/assets/landingpage/institutelogo/hcl_logo.svg",
              "/assets/landingpage/institutelogo/rec_logo.svg",
              "/assets/landingpage/institutelogo/ey_logo.png",
              "/assets/landingpage/institutelogo/dxc_logo.svg",
              "/assets/landingpage/institutelogo/daimler_logo.svg",
              "/assets/landingpage/institutelogo/adidas_logo.png",
              "/assets/landingpage/institutelogo/bajaj_logo.svg",
              "/assets/landingpage/institutelogo/rrtech_logo.svg",
              "/assets/landingpage/institutelogo/rhf_logo.png",
            ]?.map((src, idx) => (
              <div
                key={idx}
                className="bg-white flex items-center justify-center p-2 border border-[#C1C2F9] hover:shadow-md rounded"
              >
                <div className="h-[80px] w-[80px] flex items-center justify-center">
                  <img
                    src={src}
                    alt={`Logo ${idx + 1}`}
                    className="max-h-full max-w-full object-contain"
                  />
                </div>
              </div>
            ))}
          </div>
        </section>

        {/* Six Section */}
        <div className="bg-[#F7F3FF] max-sm:p-3">
          <section className="relative  max-w-7xl mx-auto py-12 grid lg:grid-cols-2 gap-10 items-start">
            <div>
              <img
                src="/assets/landingpage/Corporate_ enterprice_training.svg"
                alt=""
              />
            </div>
            <div className="mt-3">
              <h1 className=" text-[22px] md:text-[35px]  font-extrabold leading-tight">
                Corporate enterprice training delivered by Scholaracad
              </h1>

              <div className="grid grid-cols-1 md:grid-cols-2">
                <div className="flex gap-2 items-center">
                  <span>
                    <img src="/assets/landingpage/check2.svg" alt="" />
                  </span>
                  <span>
                    <p className=" text-[20px] font-semibold my-4 italic">
                      Our Locations{" "}
                    </p>
                  </span>
                </div>
                <div className="flex gap-2 items-center">
                  <span>
                    <img src="/assets/landingpage/check2.svg" alt="" />
                  </span>
                  <span>
                    <p className=" text-[20px] font-semibold my-4 italic">
                      training Delivered Globally{" "}
                    </p>
                  </span>
                </div>
                <div className="flex gap-2 items-center">
                  <span>
                    <img src="/assets/landingpage/check2.svg" alt="" />
                  </span>
                  <span>
                    <p className=" text-[20px] font-semibold my-4 italic">
                      training Delivered Globally{" "}
                    </p>
                  </span>
                </div>
                <div className="flex gap-2 items-center">
                  <span>
                    <img src="/assets/landingpage/check2.svg" alt="" />
                  </span>
                  <span>
                    <p className=" text-[20px] font-semibold my-4 italic">
                      training Delivered Globally{" "}
                    </p>
                  </span>
                </div>
              </div>
            </div>
          </section>
        </div>
      </section>
      <Footer />
    </>
  );
}

export default Course;
