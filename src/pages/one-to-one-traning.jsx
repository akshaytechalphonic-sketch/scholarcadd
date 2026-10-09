import Footer from "@/Components/Footer";
import Navbar from "@/Components/Navbar";
import React, { useState } from "react";
import { RiGraduationCapFill } from "react-icons/ri";
import Link from "next/link";
import ScheduleAppointment from "@/Components/schedule_appointment_form";
import { HiMiniArrowLongRight } from "react-icons/hi2";
import { IoCheckmark } from "react-icons/io5";
import {
  FaChalkboardTeacher,
  FaVideo,
  FaUsers,
  FaLaptop,
  FaUserGraduate,
  FaStar,
} from "react-icons/fa";
export default function LandingPage() {
  const [activeIndex, setActiveIndex] = useState(0);

  const trainingOptions = [
    {
      id: 1,
      title: "Coaching in the Classroom",
      description:
        "Group coaching for business teams that is led by an instructor in person. This method promotes teamwork, in-the-moment conversations, and practical exercises centered on strategy, communication, leadership, and performance enhancement.",
      icon: FaChalkboardTeacher,
    },
    {
      id: 2,
      title: "Live Online Coaching",
      description:
        "Online sessions that are interactive and instructed by trainers. This mode is perfect for geographically dispersed teams since it allows for live conversations, group activities, and coaching interventions",
      icon: FaVideo,
    },
    {
      id: 3,
      title: "Corporate Group Guidance",
      description:
        "Programs for coaching that are specifically designed to meet company objectives. In order to create group coaching that is in line with corporate goals, culture, and performance standards, Scholaracad collaborates closely with businesses. ",
      icon: FaUsers,
    },
    {
      id: 4,
      title: "Online Education",
      description:
        "Foundations for group coaching in conjunction with self-paced digital learning courses. Through guided group coaching sessions, participants can reinforce the core principles they have learned online",
      icon: FaLaptop,
    },
    {
      id: 5,
      title: "1-on-1 Group Coaching",
      description:
        "Group programs incorporate individualized coaching. In addition to receiving individualized attention, participants gain from group problem-solving, peer learning, and shared experiences",
      icon: FaUserGraduate,
    },
  ];
  const ActiveIcon = trainingOptions[activeIndex].icon;
  return (
    <>
      <Navbar />
      <main className="font-nunito bg-white ">
        {/* First Section */}
        <div className="Personalized_Coaching md:relative max-sm:p-3">
          <section className="relative max-w-7xl mx-auto md:py-[150px] py-12  z-10">
            <div className="flex flex-col justify-center items-center">
              <h1 className="heading_blue">Scholaracad One to One Training </h1>
              <p className="mt-4 para md:max-w-[80%] m-auto text-left">
                For professionals who want individualized attention and
                expedited skill development, Scholaracad One to One Training
                offers a tailored, goal-focused learning solution. Deeper
                comprehension, immediate feedback, and targeted problem-solving
                catered to your particular needs are all made possible by this
                training model's direct connection with knowledgeable
                instructors.
                <br />
                <br />
                Scholaracad guarantees that every session provides pertinent
                knowledge that can be instantly implemented in real-world
                circumstances by fusing practical industry insights with
                hands-on learning. Our adaptable training framework enables
                students to advance at their own speed while coordinating
                learning objectives with organizational, technical, or personal
                objectives.
              </p>
              <div className="py-5 flex gap-4">
                <Link href="/all-courses">
                  <button class="relative group border-none bg-transparent p-0  cursor-pointer  ">
                    <div class="btn_primary ">
                      <span class="select-none"> Explore Courses</span>
                      <HiMiniArrowLongRight />
                    </div>
                  </button>
                </Link>
              </div>
            </div>
          </section>
        </div>

        {/* Second Section */}
        <section className="personalized_coaching_secondsecbg max-sm:p-3">
          <div className="md:max-w-7xl mx-auto md:py-10 p-3 ">
            <h2 className="heading text-center">
              Why Choose Scholaracad One to One Training
            </h2>
            <p className="para  mt-3 ">
              With customization and professional direction, Scholaracad One to
              One Training is designed to maximize learning impact. It enables
              students to acquire competence, clarity, and self-assurance in a
              targeted environment tailored to their requirements.
            </p>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Completely customized instruction in line with personal
                  objectives.
                </p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Direct access to qualified and experienced instructors.
                </p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Adaptable timetables and personalized learning speed.
                </p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Measurable skill progress through outcome-driven sessions.
                </p>
              </div>
            </div>
          </div>
        </section>
        {/* Third Section */}
        <section className="relative  max-w-7xl mx-auto py-10 max-sm:p-3">
          <div className="text-center">
            <h2 className="heading">
              Who Can Benefit from One to One Training
            </h2>
          </div>
          <div className="flex justify-center items-center my-12">
            <img src="/assets/landingpage/Benefits_of_Focused_img.svg" alt="" />
          </div>
        </section>

        <div className="md:max-w-7xl mx-auto max-sm:p-4 py-7    ">
          <div className="bg-[#F7F3FF] rounded-md border border-gray-300 p-5">
            <h2 className=" heading ">Key Benefits of One to One Training</h2>
            <p className="para mt-3">
              By removing distractions and enabling total focus on the learner's
              goals, one-on-one training facilitates concentrated learning. This
              method guarantees deeper comprehension, quicker skill acquisition,
              and real-world application.{" "}
            </p>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Personalized education with concentrated attention{" "}
                </p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Quicker acquisition of skills and retention of knowledge{" "}
                </p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Real-time clarification and prompt feedback{" "}
                </p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Training objectives in line with professional and corporate
                  objectives{" "}
                </p>
              </div>
            </div>
          </div>
        </div>

        <section className="tranning_delivery_bg  text-[#2E318D] max-sm:p-3">
          <div className="md:max-w-7xl mx-auto py-[200px] ">
            <div className="flex justify-center items-center flex-col mt-2">
              <h2 className="heading text-center">
                Training Delivery Modes
              </h2>
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
        <section className="bg-gradient-to-b from-[#FFFFFF] to-[#F4EFFF] max-sm:p-3">
          <div className="md:max-w-7xl mx-auto  mb-10">
            <div className="w-full bg-gray-900 text-white md:p-6  shadow-lg  rounded-lg ">
              <div className=" mx-auto">
                <ScheduleAppointment />
              </div>
            </div>
          </div>
          <br />
        </section>
      </main>
      <Footer />
    </>
  );
}
