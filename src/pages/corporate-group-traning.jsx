import Footer from "@/Components/Footer";
import FormModal from "@/Components/FormModal";
import Navbar from "@/Components/Navbar";
import { useState } from "react";
import { RiGraduationCapFill } from "react-icons/ri";
import {
  FaChalkboardTeacher,
  FaVideo,
  FaUsers,
  FaLaptop,
  FaUserGraduate,
  FaStar,
} from "react-icons/fa";
import { IoCheckmark } from "react-icons/io5";
function CorporateGroupTrainning() {
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
      <section className="font-nunito bg-white  ">
        <div className="">
          <div className="grid grid-cols-1 md:grid-cols-2">
            <div className="md:max-w-[550px] md:ml-[120px] md:min-h-[75vh] mx-auto flex justify-center items-center">
              <div className=" p-5 ">
                <h1 className="heading">Business Group Coaching</h1>
                <p className="mt-2 para text-left ">
                  Business Group Coaching is a methodical,
                  goal-oriented learning methodology created to assist firms in
                  improving team performance, leadership capacity, and strategic
                  execution. Our coaching programs unite professionals in
                  supervised group environments where expert facilitation, peer
                  learning, and shared experiences produce significant and
                  long-lasting effects.
                  <br />
                  <br />
                  Scholaracad facilitates the development of vital skills,
                  alignment with organizational goals, and quantifiable
                  performance improvements by fusing real-world applications
                  with practical business insights. Businesses may implement
                  coaching solutions that fit their workforce, culture, and
                  company goals thanks to our adaptable delivery methods.
                </p>
                <div className="mt-6 flex gap-4">
                  <FormModal
                    buttonText="Download Broucher"
                    modalType="register"
                  />
                </div>
              </div>
            </div>

            <div className="relative max-sm:mt-2">
              <img
                src="/assets/landingpage/courses/course4.jpg"
                alt="Image 1"
                className="w-full h-full object-cover bg-gray-100 "
              />
              <div
                className="absolute top-0 left-0 h-full w-1/4 pointer-events-none"
                style={{
                  background: "linear-gradient(to right, white, transparent)",
                }}
              ></div>
              <div
                className="absolute top-0 left-0 w-full h-1/3 pointer-events-none"
                style={{
                  background: "linear-gradient(to bottom, white, transparent)",
                }}
              ></div>
            </div>
          </div>
        </div>
        {/* <div className="max-w-7xl mx-auto py-10 max-sm:p-3 ">
          <div className="flex justify-center items-center flex-col mt-2">
            <h1 className="md:max-w-[70%] heading text-center">
              Why Choose Scholaracad Business Group Coaching
            </h1>
            <div className="md:max-w-[80%] mt-3">
              <p className="para text-center ">
                Through the development of strong leaders, teamwork, and
                performance-driven cultures, Scholaracad Business Group Coaching
                aims to assist enterprises in achieving sustainable success. Our
                coaching ensures quantifiable results that directly support
                company goals by fusing collaborative learning with real-world
                business expertise.
              </p>
            </div>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
            <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
              <IoCheckmark className="mt-1" />
              <p className="para">
                Coaching with an enterprise focus that is in line with actual
                business issues
              </p>
            </div>
            <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
              <IoCheckmark className="mt-1" />
              <p className="para">
                Seasoned coaches with a solid background in business and
                leadership
              </p>
            </div>
            <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
              <IoCheckmark className="mt-1" />
              <p className="para">
                Adaptable delivery methods to accommodate various teams and
                locations
              </p>
            </div>
            <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
              <IoCheckmark className="mt-1" />
              <p className="para">
                Programs that are outcome-driven and have quantifiable
                performance gains
              </p>
            </div>
          </div>
        </div> */}

        <div className="md:max-w-7xl mx-auto max-sm:p-3 py-5    ">
          <div className="bg-[#F7F3FF] rounded-md border border-gray-300 p-5">
            <h2 className=" heading ">
              Why Choose Scholaracad Business Group Coaching
            </h2>
            <p className="para  mt-3 ">
              Through the development of strong leaders, teamwork, and
              performance-driven cultures, Scholaracad Business Group Coaching
              aims to assist enterprises in achieving sustainable success. Our
              coaching ensures quantifiable results that directly support
              company goals by fusing collaborative learning with real-world
              business expertise.
            </p>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Coaching with an enterprise focus that is in line with actual
                  business issues
                </p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Seasoned coaches with a solid background in business and
                  leadership
                </p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Adaptable delivery methods to accommodate various teams and
                  locations
                </p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Programs that are outcome-driven and have quantifiable
                  performance gains
                </p>
              </div>
            </div>
          </div>
        </div>

        <section className="relative max-w-7xl mx-auto py-5 grid lg:grid-cols-2 gap-10 max-sm:p-3">
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

          <div className="">
            <h2 className=" heading">
              Trusted by Leading Institutions & Companies
            </h2>
            <p className="mt-6 text-lg text-gray-600 max-w-lg">
              Thousands of learners worldwide trust us to deliver high-quality
              professional education.
            </p>
            <p className=" text-lg text-gray-500 max-w-lg mt-10">
              Rated by learners
            </p>
            <div className="flex items-center gap-2 mt-2">
              <FaStar className="text-[#E4B308]" />
              <p className="font-bold text-xl">4.8/5</p>
              <p className="text-lg text-gray-500  pl-3">12,500+ Reviews</p>
            </div>
          </div>
        </section>
          <div className="md:max-w-7xl mx-auto max-sm:p-3 py-5    ">
          <div className="bg-[#F7F3FF] rounded-md border border-gray-300 p-5">
            <h2 className=" heading ">
              Who Can Benefit from Business Group Coaching
            </h2>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">Business executives and senior leaders</p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">Emerging and mid-level managers</p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">Future and high-potential leaders</p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">Project and cross-functional teams</p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Organizations that are changing or evolving{" "}
                </p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Businesses that prioritize increasing productivity and
                  performance{" "}
                </p>
              </div>
            </div>
          </div>
        </div>
        <div className="max-sm:p-3">
          <section className="relative  max-w-7xl mx-auto py-5 grid lg:grid-cols-2 gap-10 items-start">
            <div>
              <img
                src="/assets/landingpage/Corporate_ enterprice_training.svg"
                alt=""
              />
            </div>
            <div className="">
              <h2 className="heading">
                Key Benefits of Business Group Coaching{" "}
              </h2>
              <p className=" para my-2">
                Business group coaching helps companies improve their leadership
                skills while encouraging teamwork and accountability. Through
                collaborative learning, participants acquire useful insights,
                common viewpoints, and implementable tactics that have a
                tangible impact on the workplace.
              </p>
              {/* <div className="">
                <div className="flex gap-2 items-center">
                  <span>
                    <img src="/assets/landingpage/check2.svg" alt="" />
                  </span>
                  <span>
                    <p className=" text-[20px] font-semibold my-4 italic">
                      Enhanced decision-making and leadership efficacy
                    </p>
                  </span>
                </div>
                <div className="flex gap-2 items-center">
                  <span>
                    <img src="/assets/landingpage/check2.svg" alt="" />
                  </span>
                  <span>
                    <p className=" text-[20px] font-semibold my-4 italic">
                      Increased team cohesion and cooperation
                    </p>
                  </span>
                </div>
                <div className="flex gap-2 items-center">
                  <span>
                    <img src="/assets/landingpage/check2.svg" alt="" />
                  </span>
                  <span>
                    <p className=" text-[20px] font-semibold my-4 italic">
                      Enhanced output and responsibility
                    </p>
                  </span>
                </div>
                <div className="flex gap-2 items-center">
                  <span>
                    <img src="/assets/landingpage/check2.svg" alt="" />
                  </span>
                  <span>
                    <p className=" text-[20px] font-semibold my-4 italic">
                      Long-term performance enhancement throughout the company
                    </p>
                  </span>
                </div>
              </div> */}
              <div className="grid grid-cols-1  gap-3 mt-3">
                <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                  <IoCheckmark className="mt-1" />
                  <p className="para">
                    {" "}
                    Enhanced decision-making and leadership efficacy
                  </p>
                </div>
                <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                  <IoCheckmark className="mt-1" />
                  <p className="para">
                    {" "}
                    Increased team cohesion and cooperation
                  </p>
                </div>
                <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                  <IoCheckmark className="mt-1" />
                  <p className="para"> Enhanced output and responsibility</p>
                </div>
                <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                  <IoCheckmark className="mt-1" />
                  <p className="para">
                    Long-term performance enhancement throughout the company
                  </p>
                </div>
              </div>
            </div>
          </section>
        </div>
        <section className="tranning_delivery_bg  text-[#2E318D] max-sm:p-3">
          <div className="md:max-w-7xl mx-auto py-[200px] ">
            <div className="flex justify-center items-center flex-col mt-2">
              <div>
                {/* <RiGraduationCapFill className="text-[#29A6DD] text-4xl rotate-350 ml-[-15px] mb-[-5px] " /> */}
                <hp className="font-bold text-xl  ">Training Delivery Modes</hp>
              </div>
              <h2 className="heading text-center">
                Scholaracad offers 6 learning modes to choose
              </h2>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 my-3 ">
              <div className="">
                <div class="md:max-w-md mx-auto mt-10 max-sm:p-7">
                  {/* <ul class="relative border">
                    <li class="  relative border-b py-4">
                      <span class="absolute -left-5 top-0 my-4 w-8 h-8 flex items-center justify-center bg-[#2E318D] text-white font-bold rounded">
                        01
                      </span>
                      <span class="text-[#2E318D] font-bold text-lg ml-10">
                        Classroom Training
                      </span>
                    </li>

                    <li class="  relative border-b py-4">
                      <span class="absolute -left-5 top-0 my-4 w-8 h-8 flex items-center justify-center bg-[#2E318D] text-white font-bold rounded">
                        02
                      </span>
                      <span class="text-[#2E318D] font-bold text-lg ml-10">
                        Live Virtual Training
                      </span>
                    </li>
                    <li class="  relative border-b py-4">
                      <span class="absolute -left-5 top-0 my-4 w-8 h-8 flex items-center justify-center bg-[#2E318D] text-white font-bold rounded">
                        03
                      </span>
                      <span class="text-[#2E318D] font-bold text-lg ml-10">
                        Corporate Group Training
                      </span>
                    </li>
                    <li class="  relative border-b py-4">
                      <span class="absolute -left-5 top-0 my-4 w-8 h-8 flex items-center justify-center bg-[#2E318D] text-white font-bold rounded">
                        04
                      </span>
                      <span class="text-[#2E318D] font-bold text-lg ml-10">
                        E-Learning
                      </span>
                    </li>
                    <li class="  relative border-b py-4">
                      <span class="absolute -left-5 top-0 my-4 w-8 h-8 flex items-center justify-center bg-[#2E318D] text-white font-bold rounded">
                        05
                      </span>
                      <span class="text-[#2E318D] font-bold text-lg ml-10">
                        1-to-1 Training
                      </span>
                    </li>
                    <li class="mb-2  relative  py-4 ">
                      <span class="absolute -left-5 top-0 my-4 w-8 h-8 flex items-center justify-center bg-[#2E318D] text-white font-bold rounded">
                        06
                      </span>
                      <span class="text-[#2E318D] font-bold text-lg ml-10">
                        Features 6
                      </span>
                    </li>
                  </ul> */}
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
      

        <section className="flex justify-center items-center bg-gradient-to-b from-[#FFFFFF] to-[#F4EFFF]">
          <img src="/assets/landingpage/Blogs & Post.svg" alt="" />
        </section>
      </section>
      <Footer />
    </>
  );
}

export default CorporateGroupTrainning;
export async function getStaticProps() {
  return {
    props: {
      title: "ScholarAcad | Corporate Group Training & Certification courses",
      description:
        "Scholaracad provides corporate group training with expert-led sessions, industry frameworks, and customized programs to boost productivity and growth.",
      keywords:
        "Corporate Training Programs , Corporate Group Training ,Enterprise Training Solutions , Corporate Learning & Development, Professional Corporate Training ,Enterprise Workforce Training",
    },
  };
}
