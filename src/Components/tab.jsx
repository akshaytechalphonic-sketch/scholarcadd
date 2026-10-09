import { useState } from "react";
const Tabs = ({ tabs }) => {
  const [activeTab, setActiveTab] = useState(0);
  return (
    <div className="w-full">
      <div className="flex flex-wrap gap-2 mb-6  border-gray-200">
        {tabs?.map((tab, index) => (
          <button
            key={index}
            onClick={() => setActiveTab(index)}
            className={`px-4 py-2 rounded-full max-sm:w-full border transition cursor-pointer ${
              activeTab === index
                ? "bg-[#882CFB] hover:bg-[#4347ca] text-white "
                : "bg-white text-black border-gray-300 hover:bg-gray-100"
            }`}
          >
            {tab.label}
          </button>
        ))}
      </div>
      <div className="space-y-6">
        {tabs?.map((tab, index) => (
          <div
            key={index}
            className={`${activeTab === index ? "block" : "hidden"}`}
          >
            {tab?.content}
          </div>
        ))}
      </div>
    </div>
  );
};

export default Tabs;
