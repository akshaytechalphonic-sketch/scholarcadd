import Footer from "@/Components/Footer";
import Navbar from "@/Components/Navbar";
import Testimonials from "@/Components/testimonials";
import { useState } from "react";
import { FaUserGraduate, FaCertificate, FaTools } from "react-icons/fa";
import {
  FaChalkboardTeacher,
  FaVideo,
  FaUsers,
  FaLaptop,
} from "react-icons/fa";
import { IoCheckmark } from "react-icons/io5";
const items = [
  {
    text: "Learning with a Career Focus",
    icon: <FaUserGraduate />,
    iconBg: "bg-sky-300",
    contentBg: "bg-sky-100",
  },
  {
    text: "Internationally Acclaimed Certifications",
    icon: <FaCertificate />,
    iconBg: "bg-green-300",
    contentBg: "bg-green-100",
  },
  {
    text: "Expert-Led Instruction",
    icon: <FaChalkboardTeacher />,
    iconBg: "bg-purple-300",
    contentBg: "bg-purple-100",
  },
  {
    text: "Adapting to Industry Requirements",
    icon: <FaTools />,
    iconBg: "bg-blue-300",
    contentBg: "bg-blue-100",
  },
];

function ChevronItem({ item }) {
  return (
    <div data-aos="flip-up" className="flex items-stretch  overflow-hidden">
      <div
        className={`${item.iconBg} w-24 flex items-center justify-center text-white text-xl`}
      >
        {item.icon}
      </div>
      <div
        className={`${item.contentBg} flex items-center px-6 py-4 text-gray-700 font-medium w-full clip-chevron-right`}
      >
        {item.text}
      </div>
    </div>
  );
}
function About() {
  const [activeIndex, setActiveIndex] = useState(0);

  const trainingOptions = [
    {
      id: 1,
      title: "Classroom Instruction ",
      description:
        "In person, instructor-led training events that promote communication, practical application, and instantaneous information sharing",
      icon: FaChalkboardTeacher,
    },
    {
      id: 2,
      title: "Real-Time Online Education",
      description:
        "Expert trainers provide interactive virtual sessions that allow for flexible participation and cross-location collaborative learning. ",
      icon: FaVideo,
    },
    {
      id: 3,
      title: "Business Education Solutions",
      description:
        "Programs for consultation and training that are specifically tailored to the objectives, culture, and performance standards of the firm.  ",
      icon: FaUsers,
    },
    {
      id: 4,
      title: "Online Self-Paced Education ",
      description:
        "Internet courses with structure that let students study at their own speed while using guided modules to reinforce concepts. ",
      icon: FaLaptop,
    },
    {
      id: 5,
      title: "Mentoring and Coaching",
      description:
        "Individualized and group coaching sessions with an emphasis on developing leadership, improving skills, and solving practical problems. ",
      icon: FaUserGraduate,
    },
  ];
  const ActiveIcon = trainingOptions[activeIndex].icon;
  return (
    <>
      <Navbar />
      <section className="font-nunito bg-white  ">
        <div className="aboutusbg md:relative max-sm:p-3">
          <section className="relative max-w-7xl mx-auto md:py-[100px] py-12  z-10">
            <div className="flex flex-col justify-center items-center mt-3">
              <h1 className="heading">About Us</h1>
              <p className="md:max-w-[90%] para text-left mt-2">
                Our dedication to empowering people and businesses through
                superior, industry-relevant learning solutions is shown in About
                Scholaracad. We are a platform for professional learning and
                development that specializes in providing internationally
                recognized certifications, hands-on training, coaching, and
                consulting services.
              </p>
              <p className="md:max-w-[90%] para text-left mt-2">
                In order to guarantee that students acquire both theoretical
                knowledge and immediately applicable practical skills, we at
                Scholaracad combine expert-led training with real-world
                insights. Long-term professional achievement, organizational
                excellence, and career advancement are all supported by our
                learner-centric approach.
              </p>
            </div>
          </section>
        </div>
        <div className="absolute ml-[200px]  mt-[-150px] z-20 max-sm:hidden">
          <div className="flex items-center justify-center gap-10">
            <img
              data-aos="fade-up"
              src="/assets/landingpage/aboutherosectionimg1.png"
              alt="Image 1"
              class="w-[300px] h-[300px] object-cover rounded-lg bg-gray-100"
            />
            <img
              data-aos="fade-down"
              src="/assets/landingpage/aboutherosectionimg2.png"
              alt="Image 1"
              class="w-[200px] h-[200px] object-cover rounded-lg bg-gray-100"
            />
            <img
              data-aos="fade-up"
              src="/assets/landingpage/aboutherosectionimg3.png"
              alt="Image 1"
              class="w-[300px] h-[300px] object-cover rounded-lg bg-gray-100"
            />
            <img
              data-aos="fade-down"
              src="/assets/landingpage/aboutherosectionimg4.png"
              alt="Image 1"
              class="w-[200px] h-[150px] object-cover rounded-lg bg-gray-100"
            />
          </div>
        </div>

        <section className="relative max-w-7xl mx-auto py-5  max-sm:p-3 md:mt-[220px] mt-4">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-10">
            <div data-aos="fade-up">
              <h1 className=" text-[20px] font-extrabold leading-tight mb-2 text-[#2E318D]">
                Who we are?
              </h1>
              <h1 className=" heading">
                Empower Your Business’s Financial Future Effortlessly
              </h1>
              <p className="mt-4 para  text-left">
                A worldwide supplier of professional training and certification,
                Scholaracad is dedicated to enabling people and organizations
                via superior, industry-aligned education. By assisting
                professionals in gaining in-demand skills, obtaining
                internationally recognized certifications, and filling in
                important knowledge gaps, we act as a catalyst for career
                advancement. We constantly broaden our course offerings with a
                strong emphasis on continuous improvement in order to satisfy
                the shifting learning requirements of today's workforce as well
                as changing international standards.
              </p>
            </div>
            <div className="flex flex-col gap-4">
              <div className="relative max-w-7xl mx-auto rounded-2xl overflow-hidden  ">
                <div
                  className="absolute inset-0 bg-cover bg-center"
                  style={{
                    backgroundImage:
                      "url('/assets/landingpage/ourmissionbg.png')",
                  }}
                ></div>
                <div className="relative max-sm:gap-6 p-5 text-[#2E318D]">
                  <div className=" ">
                    <h2 className="text-[20px] font-bold ">Our Mission</h2>
                    <p className="mt-2 para">
                      At ScholarAcad, our goal is to empower people and
                      organizations all around the world by offering top-notch,
                      sector-specific training and certification programs. We
                      are dedicated to assisting individuals in gaining globally
                      recognized certifications, acquiring in-demand skills, and
                      filling in important knowledge gaps that promote
                      professional development and organizational success. Our
                      goal is to provide meaningful learning experiences that
                      have a direct influence on the real world by providing
                      practical, relevant, and outcome-focused education.
                    </p>
                  </div>
                </div>
              </div>
              <div className="relative max-w-7xl mx-auto rounded-2xl overflow-hidden  ">
                <div
                  className="absolute inset-0 bg-cover bg-center"
                  style={{
                    backgroundImage:
                      "url('/assets/landingpage/ourvisionbg.png')",
                  }}
                ></div>
                <div className="relative max-sm:gap-6 p-5 text-[#2E318D]">
                  <div className=" ">
                    <h2 className="text-[20px] font-bold ">Our Vision</h2>
                    <p className="mt-2 para ">
                      By consistently improving our learning programs to satisfy
                      the shifting needs of the contemporary workforce and
                      worldwide standards, we hope to establish ourselves as a
                      globally recognized leader in professional education. Our
                      goal is to promote a culture of continual development and
                      lifelong learning so that students everywhere can adapt,
                      innovate, and succeed in a constantly shifting
                      professional environment.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div className="mt-5">
            {/* <div className="grid grid-cols-1 md:grid-cols-2 gap-4  ">
              <div className="flex flex-col gap-4">
                <div className="relative max-w-7xl mx-auto rounded-2xl overflow-hidden  ">
                  <div
                    className="absolute inset-0 bg-cover bg-center"
                    style={{
                      backgroundImage:
                        "url('/assets/landingpage/ourmissionbg.png')",
                    }}
                  ></div>
                  <div className="relative max-sm:gap-6 p-8 text-[#2E318D]">
                    <div className=" mt-12">
                      <h2 className="text-[20px] font-bold ">Our Mission</h2>
                      <p className="mt-2 text-md  text-gray-600 ">
                        To instil an Agile mindset across organizations and
                        collaborate with them on their Business Agility journey.
                      </p>
                    </div>
                  </div>
                </div>
                <div className="relative max-w-7xl mx-auto rounded-2xl overflow-hidden  ">
                  <div
                    className="absolute inset-0 bg-cover bg-center"
                    style={{
                      backgroundImage:
                        "url('/assets/landingpage/ourvisionbg.png')",
                    }}
                  ></div>
                  <div className="relative max-sm:gap-6 p-8 text-[#2E318D]">
                    <div className=" mt-12">
                      <h2 className="text-[20px] font-bold ">Our Vision</h2>
                      <p className="mt-2 text-md text-gray-600 ">
                        To be a globally-acclaimed training solutions provider
                        and to reach the 100,000 certifications mark by end
                        2025.
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              <div className="">
                <img
                  src="/assets/landingpage/aboutherosectionimg1.png"
                  alt="About Hero"
                  className="w-full  max-h-[60%] object-cover rounded-lg bg-gray-100"
                />
              </div>
            </div> */}
          </div>
        </section>
        <div className="max-sm:p-3">
          <section className="relative  max-w-7xl mx-auto py-12 grid grid-cols-1 lg:grid-cols-2 gap-5 items-start">
           <div>
             <h1 className="heading">Our Fundamental Advantages </h1>
              <p className="md:max-w-[90%] para text-left my-3">
               Delivering meaningful, career-driven learning experiences that have a genuine impact is the foundation of Scholaracad's core advantages. We ensure that every course gives professionals useful skills that are immediately relevant in real-world situations by emphasizing learning with a defined career purpose. We assist students in obtaining globally recognized qualifications that boost reputation and provide access to new professional prospects by providing internationally recognized certificates.
              </p>
             <div className="my-4 w-full md:max-w-[80%]">
                {[1].map((col) => (
                  <div key={col} className="">
                    {items?.map((item, index) => (
                      <ChevronItem key={index} item={item} />
                    ))}
                  </div>
                ))}
              </div>
           </div>
            <div className="max-sm:mt-5">
              <img
                // src="/assets/landingpage/get the_scholaracad_advantage_img.svg"
                src="/assets/landingpage/our_fundamental_advantage_img.jfif"
                alt=""
                className="rounded-md"
              />
             
            </div>
          </section>
        </div>

        <section className="relative md:max-w-7xl md:mx-auto py-5 grid lg:grid-cols-2 gap-10 max-sm:p-3">
          <div data-aos="fade-up" className="">
            <h1 className=" heading">Our Services </h1>
            <div>
              <ul className="grid grid-cols-1 md:grid-cols-2 gap-4   mt-4">
                <li className="flex md:flex-row  gap-2 items-center">
                  <IoCheckmark />
                  IT Security and Governance
                </li>
                <li className="flex md:flex-row  gap-2 items-center">
                  <IoCheckmark />
                  DevOps, and more
                </li>
                <li className="flex md:flex-row  gap-2 items-center">
                  <IoCheckmark />
                  Agile and Scrum
                </li>
                <li className="flex md:flex-row  gap-2 items-center">
                  <IoCheckmark />
                  IT Service Management
                </li>
                <li className="flex md:flex-row  gap-2 items-center">
                  <IoCheckmark />
                  Quality Management
                </li>
                <li className="flex md:flex-row  gap-2 items-center">
                  <IoCheckmark />
                  Project Management
                </li>
              </ul>
            </div>
            <p className="mt-4 para text-left">
              In order to assist people and organizations remain competitive in
              the quickly changing IT landscape of today, ScholarAcad offers a
              broad range of professional training and consulting services. Our
              courses are selected by professionals in the field and emphasize
              real-world application, internationally recognized frameworks, and
              practical expertise. We enable professionals to develop their
              abilities, boost productivity, and advance their careers by
              coordinating learning with business objectives.
            </p>
          </div>
          <div className="">
            <div
              data-aos="fade-up"
              className="bg-white shadow-md border border-gray-200 p-3 rounded-md"
            >
              <p className=" para text-left">
                IT Security and Governance, DevOps, Agile and Scrum, IT Service
                Management, Quality Management, and Project Management are just
                a few of the important areas in which we offer our services.
              </p>
            </div>
            <div
              data-aos="fade-up"
              className="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4"
            >
              <div className="shadow-md border border-gray-200 p-3 rounded-md">
                <p className=" para text-left">
                  ScholarAcad facilitates end-to-end capability development,
                  from bolstering security and compliance to facilitating faster
                  delivery through Agile and DevOps approaches.
                </p>
              </div>
              <div className="shadow-md border border-gray-200 p-3 rounded-md">
                <p className=" para text-left">
                  We help businesses create high-performing teams and promote
                  long-term success by offering flexible learning alternatives,
                  expert-led training, and certification-focused programs.
                </p>
              </div>
            </div>
          </div>
        </section>
        <section data-aos="fade-up" className="py-20 max-sm:p-3">
          <div className="relative max-w-7xl mx-auto text-center rounded-2xl overflow-hidden  ">
            <div
              className="absolute inset-0 bg-cover bg-center"
              style={{
                backgroundImage:
                  "url('/assets/landingpage/Empower Your Teams_bg.svg')",
              }}
            ></div>
            <div className="relative max-sm:gap-6 md:p-10 p-5 text-[#2E318D] md:max-w-[80%] mx-auto ">
              <div className="f ">
                <h2 className="heading_blue ">Why Choose Scholaracad</h2>
                <p className="mt-4 text-[#2E318D] font-bold md:text-lg text-sm w-full">
                  “Scholaracad is committed to developing educational
                  opportunities that are relevant to both real-world business
                  requirements and international standards. Our strategy
                  guarantees quantifiable results for both individuals and
                  corporations. ”
                </p>
                <div>
                  <ul className="grid grid-cols-1  gap-2   mt-4">
                    <li className="flex md:flex-row  gap-2 items-center">
                      <IoCheckmark />
                      Courses that are in line with industry standards and
                      internationally recognized certifications
                    </li>
                    <li className="flex md:flex-row  gap-2 items-center">
                      <IoCheckmark />
                      Skilled instructors and subject matter experts
                    </li>
                    <li className="flex md:flex-row  gap-2 items-center">
                      <IoCheckmark />
                      Adaptable learning approaches to meet the demands of a
                      variety of learners
                    </li>
                    <li className="flex md:flex-row  gap-2 items-center">
                      <IoCheckmark />
                      Programs with an emphasis on career and performance
                      advancement that are outcome-driven
                    </li>
                    <li className="flex md:flex-row  gap-2 items-center">
                      <IoCheckmark />
                      Regular revisions in line with business and market trends
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </section>
        {/* <section className="relative max-w-7xl mx-auto py-7 grid lg:grid-cols-2 gap-10 max-sm:p-3">
          <div className="">
            <h1 className=" heading">Who We Are?</h1>
            <p className="mt-4 para">
              Scholaracad, a Global Training organization, has emerged as a
              market leader in providing professional training solutions to more
              than 100,000 individuals across many Small, Medium and large
              enterprises worldwide. Scholaracad is a trusted partner who caters
              to the training and certification requirements for enterprises
              which can be customized as per the business requirements.
            </p>
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
                data-aos="flip-up"
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
        </section> */}
        <div className="md:max-w-7xl mx-auto max-sm:p-4 py-7    ">
          <div className="bg-[#F7F3FF] rounded-md border border-gray-300 p-5">
            <h2 className=" heading ">
              Who Can Benefit from Scholaracad Courses{" "}
            </h2>
            <div className="grid grid-cols-1  gap-5 mt-5">
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Students and recent graduates developing skills necessary for
                  the workforce.
                </p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Professionals in the workforce looking to advance their
                  careers and obtain certification.
                </p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Professionals in the workforce looking to advance their
                  careers and obtain certification.
                </p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">Corporate groups and companies</p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Domain experts, coaches, and consultants{" "}
                </p>
              </div>
              <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                <IoCheckmark className="mt-1" />
                <p className="para">
                  Lifelong learners dedicated to ongoing development{" "}
                </p>
              </div>
            </div>
          </div>
        </div>
        <div className="max-sm:p-3">
          <section className="relative  max-w-7xl mx-auto py-12 grid lg:grid-cols-2 gap-10 items-start">
            <div>
              <img
                src="/assets/landingpage/Corporate_ enterprice_training.svg"
                alt=""
              />
            </div>
            <div className="mt-3">
              <h2 className="heading">Key Benefits of Scholaracad </h2>
              <p className="my-4 para">
                Through organized instruction, professional advice, and
                real-world application, Scholaracad helps students and
                organizations attain excellence.{" "}
              </p>
              <div className="grid grid-cols-1  gap-5 mt-3">
                <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                  <IoCheckmark className="mt-1" />
                  <p className="para">
                    {" "}
                    Improved abilities and credibility in the workplace
                  </p>
                </div>
                <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                  <IoCheckmark className="mt-1" />
                  <p className="para">
                    {" "}
                    Promotion of one's career with accredited certifications
                  </p>
                </div>
                <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                  <IoCheckmark className="mt-1" />
                  <p className="para">
                    {" "}
                    Knowledge that is applicable to real-world situations
                  </p>
                </div>
                <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                  <IoCheckmark className="mt-1" />
                  <p className="para">
                    Adaptable and easily accessible educational opportunities{" "}
                  </p>
                </div>
                <div className="grid grid-cols-[20px_1fr] gap-2 items-start">
                  <IoCheckmark className="mt-1" />
                  <p className="para">
                    Long-term organizational and professional development
                  </p>
                </div>
              </div>
            </div>
          </section>
        </div>

        <section className="tranning_delivery_bg  text-[#2E318D] max-sm:p-3">
          <div className="md:max-w-7xl mx-auto md:py-[220px] py-[150px]">
            <div className="flex justify-center items-center flex-col mt-2">
              <h1 className="heading text-center">
                Learning and Engagement Modes
              </h1>
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

        <Testimonials />
      </section>
      <Footer />
    </>
  );
}

export default About;
export async function getStaticProps() {
  return {
    props: {
      title: "Who We Are: ScholarAcad Learning & Certification Platform",
      description:
        "Learn about ScholarAcad, a global learning platform that assists professionals build skills, earn certifications, and bridge knowledge gaps worldwide.",
      keywords:
        "About ScholarAcad, Scholaracad Online certification training , Scholaracad IT and management training, ScholarAcad learning platform",
    },
  };
}
