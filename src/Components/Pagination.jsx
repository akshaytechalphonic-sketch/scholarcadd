import React from "react";

const Pagination = ({ currentPage, totalPages, onPageChange }) => {
  const pages = [];
  for (let i = 1; i <= totalPages; i++) {
    if (
      i === 1 ||
      i === totalPages ||
      (i >= currentPage - 1 && i <= currentPage + 1)
    ) {
      pages.push(i);
    } else if (pages[pages.length - 1] !== "...") {
      pages.push("...");
    }
  }

  const handlePageChange = (page) => {
    if (page >= 1 && page <= totalPages && page !== currentPage) {
      onPageChange(page);
    }
  };

  return (
    <div className="flex justify-center items-center gap-2 mt-6">
      <button
        onClick={() => handlePageChange(currentPage - 1)}
        disabled={currentPage === 1}
        className="px-3 py-1.5 border border-slate-300 rounded-md text-xs font-semibold text-slate-600 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-[#882CFB] hover:text-white hover:border-[#882CFB] transition cursor-pointer"
      >
        Prev
      </button>

      {pages.map((page, idx) =>
        page === "..." ? (
          <span key={idx} className="px-2 py-1 text-slate-400 font-bold text-xs">
            ...
          </span>
        ) : (
          <button
            key={idx}
            onClick={() => handlePageChange(page)}
            className={`px-3 py-1.5 border rounded-md text-xs font-bold transition cursor-pointer ${
              page === currentPage
                ? "bg-[#B9A8FF] text-white border-[#B9A8FF] shadow-sm"
                : "bg-white text-slate-700 border-slate-300 hover:bg-[#882CFB] hover:text-white hover:border-[#882CFB]"
            }`}
          >
            {page}
          </button>
        )
      )}

      <button
        onClick={() => handlePageChange(currentPage + 1)}
        disabled={currentPage === totalPages}
        className="px-3 py-1.5 border border-slate-300 rounded-md text-xs font-semibold text-slate-600 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-[#882CFB] hover:text-white hover:border-[#882CFB] transition cursor-pointer"
      >
        Next
      </button>
    </div>
  );
};

export default Pagination;
