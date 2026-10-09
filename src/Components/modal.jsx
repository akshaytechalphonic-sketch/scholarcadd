import { useEffect } from "react";
import { motion, AnimatePresence } from "framer-motion";
import { IoIosClose } from "react-icons/io";

export default function Modal({
  isOpen,
  onClose,
  title,
  children,
  footer,
  width = "max-w-lg",
  height = "max-h-[80vh]",
  position = "center",
}) {
  const positionClasses = {
    center: "flex items-center justify-center",
    top: "flex justify-center items-start mt-10",
    bottom: "flex justify-center items-end mb-10",
    left: "flex items-center justify-start ml-10",
    right: "flex items-center justify-end mr-10",
  };

  useEffect(() => {
    if (isOpen) {
      document.body.style.overflow = "hidden";
    } else {
      document.body.style.overflow = "";
    }
    return () => {
      document.body.style.overflow = "";
    };
  }, [isOpen]);

  return (
    <AnimatePresence>
      {isOpen && (
        <motion.div
          className={`fixed inset-0 z-50 bg-black/50 backdrop-blur-sm ${
            positionClasses[position] || positionClasses.center
          }`}
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          exit={{ opacity: 0 }}
        >
          <motion.div
            className={`bg-white rounded-2xl shadow-xl w-full p-5 overflow-y-auto ${width} ${height}`}
            initial={{ y: -100, opacity: 0, scale: 0.95 }}
            animate={{ y: 0, opacity: 1, scale: 1 }}
            exit={{ y: 100, opacity: 0, scale: 0.95 }}
            transition={{ type: "spring", stiffness: 200, damping: 20 }}
          >
            <div className="flex justify-between items-center border-b border-gray-300 pb-3">
              <h2 className="text-lg font-bold text-black">{title}</h2>
              <button
                onClick={onClose}
                title="Close Modal"
                className="cursor-pointer flex justify-center items-center bg-[#2E318D] w-[20px] h-[20px] rounded-full text-white hover:bg-[#393ede]"
              >
                <IoIosClose className="text-2xl" />
              </button>
            </div>

            <div className="mt-4 text-black">{children}</div>
            {footer && (
              <div className="mt-6 flex justify-end gap-3">{footer}</div>
            )}
          </motion.div>
        </motion.div>
      )}
    </AnimatePresence>
  );
}
