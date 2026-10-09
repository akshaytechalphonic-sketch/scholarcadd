import { useEffect, useState } from "react";
import { IoArrowUpOutline } from "react-icons/io5";
export default function ScrollToTopButton() {
  const [isVisible, setIsVisible] = useState(false);
  useEffect(() => {
    const toggleVisibility = () => {
      if (window.scrollY > 200) {
        setIsVisible(true);
      } else {
        setIsVisible(false);
      }
    };
    window.addEventListener("scroll", toggleVisibility);
    return () => window.removeEventListener("scroll", toggleVisibility);
  }, []);
  const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  };
  return (
    <button
      onClick={scrollToTop}
      className={`fixed bottom-6 md:left-6 left-[8%] z-50 flex items-center justify-center w-12 h-12 rounded-full bg-[#882CFB] text-white shadow-lg transition-all duration-500 hover:bg-blue-700 focus:outline-none cursor-pointer max-sm:hidden
        ${
          isVisible
            ? "opacity-100 translate-y-0"
            : "opacity-0 translate-y-10 pointer-events-none"
        }
      `}
      aria-label="Scroll to top"
    >
      <IoArrowUpOutline />
    </button>
  );
}
