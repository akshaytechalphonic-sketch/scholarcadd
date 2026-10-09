import React, { useState } from "react";
import { FaMinus, FaPlus } from "react-icons/fa";
import { RiGraduationCapFill } from "react-icons/ri";
const faqs = [
  {
    question: "Why is Webflow the best nocode tool?",
    answer:
      "Yes all roof inspection are FREE of charge. We offer a free, no obligation inspection by trained professionals to determine the scope of your storm damage.",
  },
  {
    question: "When did Webflow was founded?",
    answer:
      "Webflow was founded in 2013 by Vlad Magdalin, Sergie Magdalin, and Bryant Chou to empower designers to build for the web without coding.",
  },
  {
    question: "Is Webflow better than WordPress?",
    answer:
      "Webflow offers a visual development experience, hosting, and CMS combined, making it easier for designers compared to WordPress’ plugin-based setup.",
  },
  {
    question: "Can I use Webflow for free?",
    answer:
      "Yes! Webflow offers a free starter plan that lets you design and publish projects on a Webflow.io domain.",
  },
];
function faq() {
  // FAQ JS CODE
  const [openIndex, setOpenIndex] = useState(null);
  const toggleFAQ = (index) => {
    setOpenIndex(openIndex === index ? null : index);
  };
  
  return (
    <>
      <section className="max-sm:p-3">
        <div className="max-w-7xl mx-auto md:py-10 ">
          <div className="flex justify-center flex-col mb-5">
            <RiGraduationCapFill className="text-[#29A6DD] text-4xl rotate-350 ml-[-15px] mb-[-5px] " />
            <h1 className="heading_blue md:max-w-[70%] ">
              FAQs
            </h1>
          </div>
          <section className="w-full flex flex-col md:flex-row md:flex-wrap gap-5">
            {faqs.map((faq, i) => (
              <div
                key={i}
                className={`md:w-[48%] w-full rounded-lg shadow-md transition-all duration-500 ease-in-out ${
                  openIndex === i
                    ? "bg-white text-gray-800"
                    : "bg-[#2C2C2C] text-white"
                }`}
              >
                <button
                  onClick={() => toggleFAQ(i)}
                  className="w-full flex justify-between items-center px-6 py-5 text-left font-medium text-lg focus:outline-none"
                >
                  <span>{faq.question}</span>
                  <div
                    className={`w-7 h-7 flex items-center justify-center rounded-md transition-all duration-300 ${
                      openIndex === i
                        ? "bg-gray-200 text-black rotate-180"
                        : "bg-[#9B7CFF] text-white"
                    }`}
                  >
                    {openIndex === i ? <FaMinus /> : <FaPlus />}
                  </div>
                </button>
                {/* Accordion content */}
                <div
                  className={`overflow-hidden transition-all duration-500 ease-in-out ${
                    openIndex === i
                      ? "max-h-40 opacity-100 px-6 pb-5"
                      : "max-h-0 opacity-0 px-6"
                  }`}
                >
                  <p className="text-[15px] leading-relaxed">{faq.answer}</p>
                </div>
              </div>
            ))}
          </section>
        </div>
      </section>
    </>
  );
}

export default faq;
