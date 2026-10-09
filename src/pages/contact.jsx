import Footer from "@/Components/Footer";
import Navbar from "@/Components/Navbar";
import Contactus from "@/Components/contact";

import { AiTwotoneMail } from "react-icons/ai";
import { MdLocationPin, MdPhoneInTalk } from "react-icons/md";
import { MdCall, MdChatBubble, MdEmail, MdLocationOn } from "react-icons/md";
import { IoLocationSharp } from "react-icons/io5";
import { FaExpeditedssl } from "react-icons/fa";
import { FaFacebookF } from "react-icons/fa";
import { FaYoutube } from "react-icons/fa";
import { FaXTwitter } from "react-icons/fa6";
import { FaInstagram } from "react-icons/fa";
import { FaLinkedinIn } from "react-icons/fa";
import Link from "next/link";
import { useAuth } from "@/context/AuthContext";
import { API_BASE_URL, APIENDPOINTS } from "../../apiconfig";
import { encryptId } from "@/utils/encryption";
import { useEffect, useMemo, useState } from "react";
import { HiOutlineLocationMarker } from "react-icons/hi";

function Contact() {
  const { token } = useAuth();
  const [contactdetail, setContactdetail] = useState(null);

  // FetchContactDetails Api
  useEffect(() => {
    if (!token) return;
    const FetchContactDetails = async () => {
      try {
        const res = await fetch(
          `${API_BASE_URL}${APIENDPOINTS.CONTACT_DETAILS}`,
          {
            method: "GET",
            headers: {
              "Content-Type": "application/json",
              Accept: "application/json",
              Authorization: `Bearer ${token}`,
            },
          },
        );
        if (!res.ok) {
          console.warn("HTTP error! Status:", res?.status);
          setContactdetail([]);
          return;
        }
        const data = await res.json();
        setContactdetail(data?.data || []);
      } catch (err) {
        console.error("Error fetching FetchContactDetails:", err);
      }
    };
    FetchContactDetails();
  }, [token]);
  return (
    <>
      <Navbar />
      <section className="font-nunito contactusbg  ">
        <section className="relative max-w-7xl mx-auto  py-12  z-10 max-sm:p-3  ">
          <div className="flex flex-col justify-center items-center mt-3">
            <h1 className="heading">Contact Us</h1>
            <p className=" para  mt-4">
              We at Scholaracad are here to help you at every stage of your
              career development. Our staff is here to assist you with any
              inquiries you may have regarding our certification programs,
              training schedules, course material, or enrollment procedure.
              Program Management, DevOps, Agile, IT Service Management, SAFe®,
              and IT Security and Governance are just a few of the areas in
              which we provide professional advice.
              <br />
              <br />
              Get in touch with us to find out more about our certification
              programs, instructor-led training, and personalized learning
              options tailored to your professional objectives. Our support
              staff guarantees timely responses and individualized guidance,
              assisting you in selecting the appropriate training format and
              course. Take the next assured step toward developing your
              abilities and career by getting in touch with Scholaracad right
              now.
            </p>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-[60%_40%] gap-2 my-10">
            <div>
              <div className="flex flex-col gap-1">
                <MdLocationPin className="text-[#882CFB] text-2xl" />
                <h1 className="text-[20px] font-bold">Address</h1>
              </div>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-5 mt-4">
                <div className="grid grid-cols-[auto_1fr] items-start gap-2">
                  <HiOutlineLocationMarker className="text-red-500 text-lg mt-1" />
                  <p className="para">
                    3500 S Dupont Hwy, Camden, DE 19934, United States
                  </p>
                </div>

                <div className="grid grid-cols-[auto_1fr] items-start gap-2">
                  <HiOutlineLocationMarker className="text-red-500 text-lg mt-1" />
                  <p className="para">
                    304 Scottsdale Dr, Guelph Ontario - N1G 2K8 Canada
                  </p>
                </div>

                <div className="grid grid-cols-[auto_1fr] items-start gap-2">
                  <HiOutlineLocationMarker className="text-red-500 text-lg mt-1" />
                  <p className="para">
                    13732 69 AVE MAPLE Grove, Osseo, MN 55311, USA
                  </p>
                </div>

                <div className="grid grid-cols-[auto_1fr] items-start gap-2">
                  <HiOutlineLocationMarker className="text-red-500 text-lg mt-1" />
                  <p className="para">
                    Wardian, Canary Wharf, London E14 9DY UK
                  </p>
                </div>
              </div>
            </div>
            <div>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                  <div className="flex flex-col gap-1">
                    <MdPhoneInTalk className="text-[#882CFB] text-2xl" />
                    <h1 className="text-[20px] font-bold">Phone No.</h1>
                  </div>
                  <div className="mt-5">
                    <h4 className="font-semibold flex gap-1 items-center">
                      <img
                        src="/assets/landingpage/Flag_of_India.png"
                        className=" w-[25px]"
                        alt="India Logo"
                      />
                      India
                    </h4>
                    <ul className=" mt-2 text-[15px]">
                      <li>
                        <a
                          title="Call"
                          href={`tel:${contactdetail?.phone}`}
                          className="text-[#696969]"
                        >
                          +91 {contactdetail?.phone}
                        </a>
                      </li>
                    </ul>
                    <div className="mt-4">
                      <h4 className="font-semibold flex gap-1 items-center">
                        <img
                          src="/assets/landingpage/Flag_of_US.png"
                          className=" w-[25px]"
                          alt="US Logo"
                        />
                        United Kingdom
                      </h4>
                      <ul className=" mt-2 text-[15px]">
                        <li>
                          <a href={`tel:"-744-1428-302`} className="">
                            +44 -744-1428-302
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div>
                  <div className="flex flex-col gap-1">
                    <AiTwotoneMail className="text-[#882CFB] text-2xl" />
                    <h1 className="text-[20px] font-bold">Email</h1>
                  </div>
                  <div className="mt-5 flex flex-col gap-5">
                    <a
                      title="Email"
                      href={`mailto:${contactdetail?.email}`}
                      className="text-[#696969]"
                    >
                      {contactdetail?.email}
                    </a>
                    <a
                      href={`mailto:"info@scholaracad.com`}
                      className="text-[#696969]"
                    >
                      info@scholaracad.com
                    </a>
                    <p></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div className="md:max-w-7xl mx-auto  mt-10 ">
            <div className="w-full bg-gray-900 text-white md:p-6  shadow-lg  rounded-lg max-sm:p-3">
              <h1 className=" heading_white px-2">Contact Us!</h1>
              <Contactus />
            </div>
          </div>
        </section>
      </section>
      <hr className="text-gray-200" />
      <Footer />
    </>
  );
}

export default Contact;
export async function getStaticProps() {
  return {
    props: {
      title: "Contact Us for Professional Certification",
      description:
        "For professional certification training in DevOps, Agile, Program Management, IT Service Management, and other areas, get in contact with Scholaracad",
      keywords:
        "Scholaracad Contact, Contact Scholaracad, Contact us Scholaracad",
    },
  };
}
