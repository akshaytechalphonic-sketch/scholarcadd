import React from "react";
import Link from "next/link";
import Navbar from "@/Components/Navbar";
import Footer from "@/Components/Footer";
import { DynamicSEO } from "@/lib/seoHelper";
import { FaHome, FaBookOpen, FaSearch, FaArrowLeft } from "react-icons/fa";
import { MdOutlineErrorOutline } from "react-icons/md";

export default function Custom404() {
  return (
    <>
      <DynamicSEO
        title="404 - Page Not Found | ScholarAcad"
        description="The page, course, exam simulator, or support resource you are looking for is not published or does not exist."
        robots="noindex, nofollow"
      />

      <Navbar />

      <main className="min-h-[75vh] bg-[#f8fafc] flex items-center justify-center px-4 py-16 sm:py-24">
        <div className="max-w-xl w-full text-center space-y-8 bg-white p-8 sm:p-12 rounded-3xl shadow-sm border border-slate-200/80">
          {/* Badge & Icon */}
          <div className="space-y-4">
            <div className="w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-tr from-purple-100 to-indigo-100 text-[#491689] rounded-3xl flex items-center justify-center mx-auto text-4xl sm:text-5xl shadow-inner">
              <MdOutlineErrorOutline />
            </div>

            <div className="inline-block px-4 py-1.5 rounded-full bg-purple-50 border border-purple-200 text-purple-700 text-xs font-black tracking-wider uppercase">
              Error 404 &bull; Not Found
            </div>
          </div>

          {/* Heading and explanation */}
          <div className="space-y-3">
            <h1 className="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
              Page or Resource Not Available
            </h1>
            <p className="text-sm sm:text-base text-slate-500 max-w-md mx-auto leading-relaxed">
              The course, quiz simulator, exam format, or support page you are looking for has not been published or requires special permissions.
            </p>
          </div>

          {/* Helpful Navigation CTAs */}
          <div className="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3.5">
            <Link
              href="/"
              className="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl text-xs sm:text-sm font-bold bg-[#491689] hover:bg-[#390f6e] text-white shadow-md hover:shadow-lg transition cursor-pointer"
            >
              <FaHome className="text-sm" />
              <span>Back to Home</span>
            </Link>

            <Link
              href="/all-courses"
              className="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl text-xs sm:text-sm font-bold bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 shadow-sm transition cursor-pointer"
            >
              <FaBookOpen className="text-sm text-purple-600" />
              <span>Explore All Courses</span>
            </Link>
          </div>

          {/* Helpful Note */}
          <div className="pt-4 border-t border-slate-100 text-xs text-slate-400">
            Need help? Contact our academic support team at{" "}
            <a
              href="mailto:support@scholaracad.com"
              className="text-[#491689] hover:underline font-semibold"
            >
              support@scholaracad.com
            </a>
          </div>
        </div>
      </main>

      <Footer />
    </>
  );
}
