import { useState, useEffect, Children, cloneElement } from "react";

export default function Accordion({
  children,
  singleOpen = false,
  defaultOpen = [],
  className = "",
}) {
  const [openIndexes, setOpenIndexes] = useState([]);
  useEffect(() => {
    if (typeof defaultOpen === "number") {
      setOpenIndexes([defaultOpen]);
    } else if (Array.isArray(defaultOpen)) {
      setOpenIndexes(defaultOpen);
    }
  }, [defaultOpen]);
  const handleToggle = (index) => {
    setOpenIndexes((prev) => {
      if (singleOpen) {
        return prev.includes(index) ? [] : [index];
      } else {
        return prev.includes(index)
          ? prev.filter((i) => i !== index)
          : [...prev, index];
      }
    });
  };
  return (
    <div className={`w-full ${className}`}>
      {Children.map(children, (child, index) =>
        cloneElement(child, {
          isOpen: openIndexes.includes(index),
          onToggle: () => handleToggle(index),
        })
      )}
    </div>
  );
}
Accordion.Item = function AccordionItem({ title, isOpen, onToggle, children }) {
  return (
    <div className="bg-white border border-[#29A6DD] rounded-lg shadow mb-4 overflow-hidden cup">
      <button
        onClick={onToggle}
        className="w-full flex justify-between cursor-pointer items-center px-4 py-3 text-left hover:bg-gray-50 transition-colors font-medium text-gray-800 border-b-1 border-[#29A6DD] "
      >
        <span className="text-lg font-bold">{title}

          
        </span>
        <span className="text-[#29A6DD] text-xl font-bold">
          {isOpen ? "-" : "+"}
        </span>
      </button>

      
      <div
        className={`overflow-hidden transition-[max-height,opacity] duration-500 ease-in-out ${
          isOpen ? "max-h-[2000px] opacity-100" : "max-h-0 opacity-0"
        }`}
      >
        <div className="px-4 py-3 text-gray-600">
          {children}
        </div>
        </div>
    </div>
  );
};


{/* <Accordion singleOpen defaultOpen={0}>
              {items.slice(0, showCount).map((faq) => (
                <Accordion.Item key={faq?.id} title={faq?.title}>
                  <div
                    className="text-gray-700 summernote-content"
                    dangerouslySetInnerHTML={{ __html: faq?.description }}
                  />
                </Accordion.Item>
              ))}
            </Accordion> */}