import React, { useEffect, useState, useMemo } from 'react';
import Head from 'next/head';
import Link from 'next/link';
import Navbar from '@/Components/Navbar';
import Footer from '@/Components/Footer';
import Loader from '@/Components/loader';
import FormModal from '@/Components/FormModal';
import { DynamicSEO, generateSupportPageSchema } from "@/lib/seoHelper";
import { API_BASE_URL } from '../../apiconfig';
import { getCourseImageUrl } from '@/utils/imageHelper';
import {
  FaChevronDown,
  FaChevronUp,
  FaCheckCircle,
  FaCheckSquare,
  FaBook,
  FaHome,
  FaDownload,
  FaGraduationCap,
  FaRegClock,
  FaStar,
  FaAward,
} from 'react-icons/fa';
import { FcGoogle } from 'react-icons/fc';
import { IoLogoFacebook } from 'react-icons/io5';
import { FaSquareInstagram } from 'react-icons/fa6';
import { IoIosArrowForward } from 'react-icons/io';
import { MdOutlineSchool, MdOutlineMenuBook, MdVerified } from 'react-icons/md';
import { HiMiniArrowLongRight } from 'react-icons/hi2';

const replaceBrandColors = (rawHtml) => {
  if (!rawHtml) return '';
  return rawHtml
    .replace(
      /linear-gradient\(135deg,\s*#1e3a8a\s*0%,\s*#2563eb\s*100%\)/gi,
      'linear-gradient(135deg, #882CFB 0%, #4347CA 100%)'
    )
    .replace(
      /linear-gradient\([^)]*?(?:#1e3a8a|#2563eb|#1d4ed8|#2e318d)[^)]*?\)/gi,
      'linear-gradient(135deg, #882CFB 0%, #4347CA 100%)'
    )
    .replace(
      /(background(?:-color)?\s*:\s*)(?:#2563eb|#1d4ed8|#1e40af|#1e3a8a|#2e318d|#3b82f6|rgb\(\s*37\s*,\s*99\s*,\s*235\s*\)|rgb\(\s*29\s*,\s*78\s*,\s*216\s*\)|rgb\(\s*30\s*,\s*58\s*,\s*138\s*\)|rgb\(\s*46\s*,\s*49\s*,\s*141\s*\))/gi,
      '$1#882CFB'
    )
    .replace(/\bbg-blue-(?:500|600|700|800|900)\b/gi, 'bg-[#882CFB]')
    .replace(/\btext-blue-(?:500|600|700|800)\b/gi, 'text-[#882CFB]')
    .replace(/\bborder-blue-(?:500|600|700|800)\b/gi, 'border-[#882CFB]');
};

const safeHtml = (html) => {
  if (!html) return '';
  const transformed = replaceBrandColors(html);
  if (typeof window === 'undefined') return transformed;
  try {
    const DOMPurify = require('dompurify');
    return DOMPurify.sanitize(transformed);
  } catch (e) {
    return transformed;
  }
};

export default function DedicatedSyllabusPage({
  courseData,
  loading = false,
  active_country,
  url_title,
  pageType = "syllabus",
}) {
  const [openModule, setOpenModule] = useState(0);
  const [isMounted, setIsMounted] = useState(false);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [activeTab, setActiveTab] = useState("curriculum");

  useEffect(() => {
    setIsMounted(true);
  }, []);

  const pageLabel = useMemo(() => {
    if (!pageType) return "Syllabus";
    return pageType
      .split("-")
      .map((w) => w.charAt(0).toUpperCase() + w.slice(1))
      .join(" ");
  }, [pageType]);

  const courseTitle = useMemo(
    () =>
      courseData?.support_page?.title ||
      courseData?.course?.course_title ||
      courseData?.course?.name ||
      courseData?.course_title ||
      courseData?.courseHeading?.title ||
      courseData?.dynamicCourseHeading?.heading ||
      (url_title ? url_title.replace(/-/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase()) : `Course ${pageLabel}`),
    [courseData, url_title, pageLabel]
  );

  const courseDescription = useMemo(
    () =>
      courseData?.seo?.description ||
      courseData?.course?.short_description ||
      courseData?.courseDescription?.description ||
      courseData?.dynamicCourseHeading?.description ||
      '',
    [courseData]
  );

  const curriculumHtml = useMemo(
    () =>
      courseData?.support_page?.content ||
      courseData?.courseDescription?.curriculum ||
      courseData?.curriculum ||
      '',
    [courseData]
  );

  const breadcrumb = useMemo(() => {
    return Array.isArray(courseData?.breadcrumb) && courseData.breadcrumb.length > 0
      ? courseData.breadcrumb
      : null;
  }, [courseData]);

  const pointers = useMemo(() => {
    try {
      if (courseData?.whatyoulearn?.pointers) {
        return typeof courseData.whatyoulearn.pointers === 'string'
          ? JSON.parse(courseData.whatyoulearn.pointers)
          : courseData.whatyoulearn.pointers;
      }
      if (courseData?.bulletPoints) {
        return typeof courseData.bulletPoints === 'string'
          ? JSON.parse(courseData.bulletPoints)
          : Array.isArray(courseData.bulletPoints)
          ? courseData.bulletPoints
          : [];
      }
      return [];
    } catch (e) {
      return [];
    }
  }, [courseData]);

  const pdfUrl = useMemo(() => {
    if (courseData?.coursePDF?.curriculum_pdf_name) {
      return `${API_BASE_URL}/assets/courseCurriculum/${courseData.coursePDF.curriculum_pdf_name}`;
    }
    return null;
  }, [courseData]);

  const courseImage = useMemo(() => {
    const raw =
      courseData?.header_image ||
      courseData?.course?.header_image ||
      courseData?.support_page?.image ||
      courseData?.courseImage?.banner_image_path ||
      courseData?.certificationImage?.image_path ||
      courseData?.dynamicCourseHeading?.header_image;
    const defaultCourse =
      url_title && url_title.toLowerCase().includes('prince2')
        ? '/assets/img/prince2.jpg'
        : '/assets/landingpage/courses/course1.jpg';
    return getCourseImageUrl(raw, defaultCourse);
  }, [courseData, url_title]);

  const certificateImage = useMemo(() => {
    const raw =
      courseData?.certificationImage?.image_path ||
      courseData?.courseImage?.banner_image_path;
    return raw ? getCourseImageUrl(raw, '/assets/images/new-dynamic-course/certificate.jpg') : null;
  }, [courseData]);

  const faqs = useMemo(() => {
    return Array.isArray(courseData?.faqs) ? courseData.faqs : [];
  }, [courseData]);

  const courseSlug = url_title || '';
  const mainCourseUrl = `/${courseSlug}`;

  const canonicalPath = `/${courseSlug}/${pageType}`;
  const displayHeading = useMemo(() => {
    const title = (courseTitle || '').trim();
    const label = (pageLabel || '').trim();
    if (title.toLowerCase().endsWith(label.toLowerCase())) {
      return title;
    }
    return `${title} ${label}`;
  }, [courseTitle, pageLabel]);
  const seoTitle = courseData?.seo?.title || `${displayHeading} - Complete Modules & Curriculum | ScholarAcad`;
  const seoDesc = courseData?.seo?.description || `Detailed ${pageLabel.toLowerCase()}, module breakdown, learning objectives, and exam preparation for ${courseTitle}.`;

  const schemaBreadcrumbs = useMemo(() => {
    if (breadcrumb && breadcrumb.length > 0) {
      return breadcrumb.map((b) => ({
        name: b.name,
        url: b.url?.startsWith("http") ? b.url.replace(/\/in\//g, "/") : b.url ? b.url.replace(/^\/in\//, "/") : mainCourseUrl
      }));
    }
    return [
      { name: "Home", url: "/" },
      { name: courseTitle, url: mainCourseUrl },
      { name: pageLabel, url: canonicalPath }
    ];
  }, [breadcrumb, courseTitle, mainCourseUrl, pageLabel, canonicalPath]);

  const schemaData = useMemo(() => {
    return generateSupportPageSchema({
      title: seoTitle,
      description: seoDesc,
      pageUrl: canonicalPath,
      breadcrumbs: schemaBreadcrumbs,
      faqs: faqs
    });
  }, [seoTitle, seoDesc, canonicalPath, schemaBreadcrumbs, faqs]);

  if (loading && !courseData) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gray-50">
        <Loader />
      </div>
    );
  }

  if (!loading && (!courseData || (!courseData?.support_page && !courseData?.courseDescription?.curriculum && !courseData?.curriculum))) {
    return (
      <>
        <DynamicSEO
          title={`404 - ${pageLabel} Not Found | ScholarAcad`}
          description={`The ${pageLabel.toLowerCase()} page for this course is not published or available.`}
          robots="noindex, nofollow"
        />
        <Navbar />
        <main className="min-h-[70vh] bg-[#f8fafc] flex items-center justify-center px-4 py-16">
          <div className="max-w-md w-full bg-white rounded-3xl p-8 sm:p-10 text-center shadow-sm border border-slate-200/80 space-y-6">
            <div className="w-20 h-20 bg-purple-50 text-[#882CFB] rounded-2xl flex items-center justify-center mx-auto text-4xl shadow-inner">
              <MdOutlineMenuBook />
            </div>
            <div className="space-y-2">
              <span className="text-xs font-black tracking-widest text-[#882CFB] uppercase bg-purple-100/60 px-3.5 py-1 rounded-full">
                404 Not Found
              </span>
              <h1 className="text-2xl font-black text-slate-900 tracking-tight">
                {pageLabel} Not Available
              </h1>
              <p className="text-xs sm:text-sm text-slate-500 leading-relaxed">
                The {pageLabel.toLowerCase()} support page for &quot;{courseTitle}&quot; has not been published or requires special permissions.
              </p>
            </div>
            <div className="pt-2 flex flex-col sm:flex-row gap-3">
              <Link
                href={mainCourseUrl}
                className="flex-1 py-3 px-4 rounded-xl text-xs font-bold bg-[#882CFB] hover:bg-[#701cdb] text-white shadow-md transition"
              >
                View Course Overview
              </Link>
              <Link
                href="/all-courses"
                className="py-3 px-4 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition"
              >
                Browse All Courses
              </Link>
            </div>
          </div>
        </main>
        <Footer />
      </>
    );
  }

  return (
    <>
      <DynamicSEO
        title={seoTitle}
        description={seoDesc}
        keywords={courseData?.seo?.keywords || `${courseTitle}, ${pageLabel.toLowerCase()}, syllabus, exam format, curriculum`}
        canonicalUrl={canonicalPath}
        ogType="article"
        ogImage={courseImage}
        schemaData={schemaData}
      />

      <Navbar />

      <main className="min-h-screen bg-white text-gray-800 font-nunito">
        {/* Top Tab Bar (Matching Image 3 & 5 style) */}
        <div className="bg-white border-b border-gray-200 sticky top-0 z-30 shadow-xs">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="flex items-center space-x-6 overflow-x-auto py-3 scrollbar-none text-xs sm:text-sm font-semibold text-gray-600">
              <button
                onClick={() => {
                  setActiveTab("curriculum");
                  const el = document.getElementById("curriculum-section");
                  if (el) el.scrollIntoView({ behavior: "smooth" });
                }}
                className={`whitespace-nowrap pb-1 transition border-b-2 ${
                  activeTab === "curriculum"
                    ? "border-[#882CFB] text-[#882CFB] font-bold"
                    : "border-transparent hover:text-[#882CFB]"
                }`}
              >
                Curriculum Modules
              </button>
              <Link
                href={mainCourseUrl}
                className="whitespace-nowrap pb-1 border-b-2 border-transparent hover:text-[#882CFB] transition"
              >
                Course Highlights
              </Link>
              <Link
                href={mainCourseUrl}
                className="whitespace-nowrap pb-1 border-b-2 border-transparent hover:text-[#882CFB] transition"
              >
                Course Overview
              </Link>
              {pointers && pointers.length > 0 && (
                <button
                  onClick={() => {
                    setActiveTab("whatyoulearn");
                    const el = document.getElementById("whatyoulearn-section");
                    if (el) el.scrollIntoView({ behavior: "smooth" });
                  }}
                  className={`whitespace-nowrap pb-1 transition border-b-2 ${
                    activeTab === "whatyoulearn"
                      ? "border-[#882CFB] text-[#882CFB] font-bold"
                      : "border-transparent hover:text-[#882CFB]"
                  }`}
                >
                  What You&apos;ll Learn
                </button>
              )}
              {faqs && faqs.length > 0 && (
                <button
                  onClick={() => {
                    setActiveTab("faqs");
                    const el = document.getElementById("faqs-section");
                    if (el) el.scrollIntoView({ behavior: "smooth" });
                  }}
                  className={`whitespace-nowrap pb-1 transition border-b-2 ${
                    activeTab === "faqs"
                      ? "border-[#882CFB] text-[#882CFB] font-bold"
                      : "border-transparent hover:text-[#882CFB]"
                  }`}
                >
                  FAQs
                </button>
              )}
            </div>
          </div>
        </div>

        {/* Breadcrumb Header */}
        <div className="bg-[#FAF8FE] border-b border-gray-100">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            {breadcrumb ? (
              <nav className="flex items-center space-x-2 text-xs sm:text-sm text-gray-500 overflow-x-auto whitespace-nowrap">
                {breadcrumb.map((b, idx) => {
                  const cleanedUrl = b.url?.startsWith('http')
                    ? b.url.replace(/\/in\//g, '/')
                    : b.url
                    ? b.url.replace(/^\/in\//, '/')
                    : mainCourseUrl;
                  return (
                    <React.Fragment key={idx}>
                      {idx > 0 && <IoIosArrowForward className="text-gray-400 text-xs shrink-0" />}
                      {idx === breadcrumb.length - 1 ? (
                        <span className="text-[#882CFB] font-semibold">{b.name}</span>
                      ) : (
                        <Link
                          href={idx === 0 ? '/' : cleanedUrl}
                          className="hover:text-[#882CFB] transition truncate max-w-[200px]"
                        >
                          {idx === 0 ? (
                            <span className="flex items-center">
                              <FaHome className="mr-1 text-sm text-[#2E318D]" /> {b.name}
                            </span>
                          ) : (
                            b.name
                          )}
                        </Link>
                      )}
                    </React.Fragment>
                  );
                })}
              </nav>
            ) : (
              <nav className="flex items-center space-x-2 text-xs sm:text-sm text-gray-500 overflow-x-auto whitespace-nowrap">
                <Link
                  href="/"
                  className="flex items-center hover:text-[#882CFB] transition"
                >
                  <FaHome className="mr-1 text-sm text-[#2E318D]" /> Home
                </Link>
                <IoIosArrowForward className="text-gray-400 text-xs shrink-0" />
                <Link
                  href="/all-courses"
                  className="hover:text-[#882CFB] transition"
                >
                  Courses
                </Link>
                <IoIosArrowForward className="text-gray-400 text-xs shrink-0" />
                <Link
                  href={mainCourseUrl}
                  className="hover:text-[#882CFB] transition capitalize truncate max-w-[220px]"
                >
                  {courseTitle}
                </Link>
                <IoIosArrowForward className="text-gray-400 text-xs shrink-0" />
                <span className="text-[#882CFB] font-semibold">{pageLabel}</span>
              </nav>
            )}
          </div>
        </div>

        {/* Clean Light Hero Section (Pattern matching Image 3) */}
        <section className="bg-white py-8 lg:py-12 border-b border-gray-100">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
              {/* Left Column: Course Image Card (Clean white card, matching Image 3) */}
              <div className="lg:col-span-5 flex justify-center lg:justify-start">
                <div className="relative w-full max-w-[480px] rounded-2xl border border-gray-200/90 bg-white p-3 shadow-md hover:shadow-lg transition-all flex items-center justify-center min-h-[240px] group">
                  <img
                    src={courseImage}
                    alt={courseTitle}
                    onError={(e) => {
                      e.currentTarget.src =
                        url_title && url_title.toLowerCase().includes('prince2')
                          ? '/assets/img/prince2.jpg'
                          : '/assets/landingpage/courses/course1.jpg';
                    }}
                    className="w-full h-auto max-h-[320px] object-contain rounded-xl group-hover:scale-105 transition-transform duration-500"
                  />
                </div>
              </div>

              {/* Right Column: Title, Ratings, Highlights, CTA */}
              <div className="lg:col-span-7 space-y-4">
                <div className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-50 text-[#882CFB] border border-purple-200 text-xs font-bold uppercase tracking-wider">
                  <MdOutlineSchool className="text-sm" />
                  {pageLabel} & Modules
                </div>

                <h1 className="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-gray-900 leading-tight">
                  {displayHeading}
                </h1>

                {courseDescription && (
                  <p className="text-sm sm:text-base text-gray-600 leading-relaxed">
                    {courseDescription}
                  </p>
                )}

                {/* Ratings row (matching Image 3) */}
                <div className="flex items-center flex-wrap gap-4 pt-1">
                  <div className="flex items-center gap-4">
                    <div className="flex items-center gap-1 text-base sm:text-lg">
                      <FcGoogle />
                      <FaStar className="text-yellow-400 text-sm" />
                      <span className="text-black font-semibold text-sm">5.0</span>
                    </div>
                    <div className="flex items-center gap-1 text-base sm:text-lg text-[#1877F2]">
                      <IoLogoFacebook />
                      <FaStar className="text-yellow-400 text-sm" />
                      <span className="text-black font-semibold text-sm">5.0</span>
                    </div>
                    <div className="flex items-center gap-1 text-base sm:text-lg text-[#E1306C]">
                      <FaSquareInstagram />
                      <FaStar className="text-yellow-400 text-sm" />
                      <span className="text-black font-semibold text-sm">5.0</span>
                    </div>
                  </div>
                  <div className="h-5 w-px bg-gray-300"></div>
                  <span className="text-sm font-medium text-gray-500 whitespace-nowrap">
                    6000+ Enrolled
                  </span>
                </div>

                {/* Highlight Checkmark Points */}
                <div className="space-y-2.5 pt-2">
                  <div className="flex items-start gap-2.5">
                    <FaCheckSquare className="text-[#882CFB] text-base shrink-0 mt-0.5" />
                    <p className="text-xs sm:text-sm text-gray-700 leading-snug">
                      Comprehensive module coverage aligned with the latest exam objectives
                    </p>
                  </div>
                  <div className="flex items-start gap-2.5">
                    <FaCheckSquare className="text-[#882CFB] text-base shrink-0 mt-0.5" />
                    <p className="text-xs sm:text-sm text-gray-700 leading-snug">
                      Hands-on practical case studies & real-world scenario implementations
                    </p>
                  </div>
                  <div className="flex items-start gap-2.5">
                    <FaCheckSquare className="text-[#882CFB] text-base shrink-0 mt-0.5" />
                    <p className="text-xs sm:text-sm text-gray-700 leading-snug">
                      Instructor-led training support with post-curriculum exam mentorship
                    </p>
                  </div>
                </div>

                {/* Action Buttons */}
                <div className="pt-4 flex flex-wrap items-center gap-3.5">
                  <button
                    onClick={() => setIsModalOpen(true)}
                    className="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-[#882CFB] hover:bg-[#7221D8] text-white font-semibold text-sm shadow-md hover:shadow-purple-500/20 transition transform hover:-translate-y-0.5 cursor-pointer"
                  >
                    <span>Request Curriculum</span>
                    <HiMiniArrowLongRight className="text-lg" />
                  </button>

                  {pdfUrl && (
                    <a
                      href={pdfUrl}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-300 font-semibold text-sm transition shadow-xs"
                    >
                      <FaDownload className="text-amber-600" />
                      <span>Download PDF</span>
                    </a>
                  )}

                  <Link
                    href={mainCourseUrl}
                    className="inline-flex items-center gap-1.5 px-5 py-3 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm transition"
                  >
                    Course Overview
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* 2-Column Body Section (Pattern matching Image 4 & 5) */}
        <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            {/* Left Sidebar (Image 4 pattern: Corporate Training & Trending Courses) */}
            <div className="lg:col-span-4 space-y-5 lg:sticky lg:top-16">
              {/* Corporate Training Card (Peach background matching Image 4) */}
              <div className="relative rounded-2xl bg-[#F5DFD0] border border-orange-200/80 p-5 shadow-xs overflow-hidden group">
                <h3 className="text-base font-bold text-gray-900 tracking-wider uppercase mb-2">
                  CORPORATE TRAINING
                </h3>
                <ul className="text-sm text-gray-800 space-y-1.5 mb-4">
                  <li className="list-disc ml-4">Wide range of Training dates</li>
                  <li className="list-disc ml-4">Impactful Instructor-Led training</li>
                </ul>
                <button
                  type="button"
                  onClick={() => setIsModalOpen(true)}
                  className="inline-flex items-center gap-1.5 px-5 py-2 rounded-full bg-[#882CFB] hover:bg-[#7221D8] text-white text-xs font-semibold shadow-sm transition"
                >
                  <span>Contact For Training</span>
                  <HiMiniArrowLongRight />
                </button>
              </div>

              {/* Trending Courses Card (Mint background matching Image 4) */}
              <div className="relative rounded-2xl bg-[#E1F3F0] border border-cyan-200/80 p-5 shadow-xs overflow-hidden group">
                <h3 className="text-base font-bold text-gray-900 tracking-wider uppercase mb-2">
                  TRENDING COURSES
                </h3>
                <ul className="text-sm text-gray-800 space-y-1.5 mb-4">
                  <li className="list-disc ml-4">Wide range of Training courses</li>
                  <li className="list-disc ml-4">Impactful Instructor-Led training</li>
                </ul>
                <Link
                  href="/all-courses"
                  className="inline-flex items-center gap-1.5 px-5 py-2 rounded-full bg-[#882CFB] hover:bg-[#7221D8] text-white text-xs font-semibold shadow-sm transition"
                >
                  <span>View Courses</span>
                  <HiMiniArrowLongRight />
                </Link>
              </div>

              {/* Course Summary Card */}
              <div className="bg-white rounded-2xl p-5 border border-gray-200 shadow-xs">
                <h3 className="text-sm font-bold text-gray-900 mb-3 pb-2 border-b border-gray-100 uppercase tracking-wider">
                  Course Summary
                </h3>
                <ul className="space-y-3 text-xs sm:text-sm">
                  <li className="flex items-center justify-between text-gray-600">
                    <span className="flex items-center gap-2">
                      <FaRegClock className="text-[#882CFB]" /> Mode
                    </span>
                    <span className="font-semibold text-gray-800">Live Instructor-Led</span>
                  </li>
                  <li className="flex items-center justify-between text-gray-600">
                    <span className="flex items-center gap-2">
                      <MdOutlineMenuBook className="text-[#882CFB]" /> Access
                    </span>
                    <span className="font-semibold text-gray-800">Lifetime LMS Access</span>
                  </li>
                  <li className="flex items-center justify-between text-gray-600">
                    <span className="flex items-center gap-2">
                      <FaAward className="text-[#882CFB]" /> Certificate
                    </span>
                    <span className="font-semibold text-gray-800">Official Accredited</span>
                  </li>
                  <li className="flex items-center justify-between text-gray-600">
                    <span className="flex items-center gap-2">
                      <MdVerified className="text-[#882CFB]" /> Passing Rate
                    </span>
                    <span className="font-semibold text-green-600">99.2%</span>
                  </li>
                </ul>
                <div className="mt-4 pt-3 border-t border-gray-100">
                  <button
                    onClick={() => setIsModalOpen(true)}
                    className="w-full py-2.5 px-4 rounded-xl bg-[#882CFB] hover:bg-[#7221D8] text-white text-xs font-semibold text-center shadow-xs transition"
                  >
                    Enquire for Next Batch
                  </button>
                </div>
              </div>
            </div>

            {/* Right Main Content (Detailed Modules, What you'll learn, Certificate, FAQs) */}
            <div className="lg:col-span-8 space-y-8">
              {/* Detailed Course Modules & Curriculum Container (Lavender card matching Image 4) */}
              <div id="curriculum-section" className="bg-[#FAF8FE] border border-purple-100 rounded-2xl p-6 sm:p-8 shadow-xs">
                <div className="flex flex-wrap items-center justify-between gap-4 mb-5 pb-3 border-b border-purple-100/70">
                  <div>
                    <h2 className="text-xl sm:text-2xl font-bold text-gray-900 flex items-center gap-2">
                      <FaBook className="text-[#882CFB]" />
                      Detailed Course Modules & Curriculum
                    </h2>
                    <p className="text-xs sm:text-sm text-gray-500 mt-1">
                      Step-by-step module breakdown prepared by certified instructors
                    </p>
                  </div>
                  {pdfUrl && (
                    <a
                      href={pdfUrl}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="text-xs font-semibold text-[#882CFB] hover:text-[#4347ca] flex items-center gap-1.5"
                    >
                      <FaDownload /> Download PDF
                    </a>
                  )}
                </div>

                {curriculumHtml ? (
                  <div
                    className="prose max-w-none prose-headings:font-bold prose-headings:text-gray-900 prose-p:text-gray-700 prose-li:text-gray-700"
                    dangerouslySetInnerHTML={{
                      __html: safeHtml(curriculumHtml),
                    }}
                  />
                ) : (
                  <div className="space-y-3.5">
                    <div className="p-4 rounded-xl border border-gray-200 bg-white">
                      <h3 className="font-semibold text-gray-900 text-sm mb-1">
                        Module 1: Introduction & Foundational Principles
                      </h3>
                      <p className="text-xs sm:text-sm text-gray-600">
                        Core concepts, industry terminology, foundational framework, and lifecycle phases.
                      </p>
                    </div>
                    <div className="p-4 rounded-xl border border-gray-200 bg-white">
                      <h3 className="font-semibold text-gray-900 text-sm mb-1">
                        Module 2: Practical Implementation & Best Practices
                      </h3>
                      <p className="text-xs sm:text-sm text-gray-600">
                        Real-world case studies, hands-on scenarios, and stakeholder collaboration.
                      </p>
                    </div>
                    <div className="p-4 rounded-xl border border-gray-200 bg-white">
                      <h3 className="font-semibold text-gray-900 text-sm mb-1">
                        Module 3: Certification Exam Preparation & Simulation
                      </h3>
                      <p className="text-xs sm:text-sm text-gray-600">
                        Mock questions, review strategies, time management, and exam day guidelines.
                      </p>
                    </div>
                  </div>
                )}
              </div>

              {/* What You Will Master (Image 3 / Highlights style) - only if data exists */}
              {pointers && pointers.length > 0 && (
                <div id="whatyoulearn-section" className="bg-white rounded-2xl p-6 sm:p-8 shadow-xs border border-gray-200/90">
                  <h2 className="text-xl sm:text-2xl font-bold text-gray-900 mb-5 flex items-center gap-2">
                    <FaGraduationCap className="text-[#882CFB]" />
                    What You Will Master in This Course
                  </h2>
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    {pointers.map((item, idx) => (
                      <div
                        key={idx}
                        className="flex items-start gap-2.5 p-3 rounded-xl bg-slate-50 border border-slate-100 hover:border-purple-200 transition"
                      >
                        <FaCheckCircle className="text-green-500 mt-1 shrink-0 text-sm" />
                        <span className="text-xs sm:text-sm font-medium text-gray-700 leading-snug">
                          {typeof item === 'string' ? item : item?.title || JSON.stringify(item)}
                        </span>
                      </div>
                    ))}
                  </div>
                </div>
              )}

              {/* Official Certificate Preview Card - only if certificate exists */}
              {certificateImage && (
                <div className="bg-white rounded-2xl p-6 sm:p-8 shadow-xs border border-gray-200/90 flex flex-col md:flex-row items-center gap-6">
                  <div className="w-full md:w-1/2 rounded-xl overflow-hidden border border-gray-200 bg-slate-50 p-2">
                    <img
                      src={certificateImage}
                      alt="Official Certificate"
                      onError={(e) => {
                        e.currentTarget.src = '/assets/images/new-dynamic-course/certificate.jpg';
                      }}
                      className="w-full h-auto object-contain rounded-lg"
                    />
                  </div>
                  <div className="w-full md:w-1/2 space-y-2.5">
                    <span className="inline-block text-[11px] font-bold text-[#882CFB] uppercase tracking-wider bg-purple-50 px-2.5 py-1 rounded-md">
                      Accreditation
                    </span>
                    <h3 className="text-lg sm:text-xl font-bold text-gray-900">
                      Official Global Certificate
                    </h3>
                    <p className="text-xs sm:text-sm text-gray-600 leading-relaxed">
                      Upon completing the curriculum modules and clearing the evaluation, you will receive an industry-recognized credential to boost your career.
                    </p>
                    <div className="pt-2">
                      <button
                        onClick={() => setIsModalOpen(true)}
                        className="px-5 py-2.5 rounded-full bg-[#882CFB] hover:bg-[#7221D8] text-white font-semibold text-xs shadow-sm transition"
                      >
                        Enquire for Certificate
                      </button>
                    </div>
                  </div>
                </div>
              )}

              {/* FAQs Section - only if faqs exist */}
              {faqs && faqs.length > 0 && (
                <div id="faqs-section" className="bg-white rounded-2xl p-6 sm:p-8 shadow-xs border border-gray-200/90">
                  <h2 className="text-xl sm:text-2xl font-bold text-gray-900 mb-5">
                    Frequently Asked Questions ({pageLabel})
                  </h2>
                  <div className="space-y-3">
                    {faqs.map((faq, idx) => (
                      <div
                        key={idx}
                        className="border border-gray-200 rounded-xl overflow-hidden"
                      >
                        <button
                          onClick={() => setOpenModule(openModule === idx ? -1 : idx)}
                          className="w-full flex items-center justify-between p-4 text-left font-semibold text-gray-800 hover:bg-gray-50 transition text-sm"
                        >
                          <span>{faq?.question || faq?.title}</span>
                          {openModule === idx ? (
                            <FaChevronUp className="text-[#882CFB] shrink-0 ml-2" />
                          ) : (
                            <FaChevronDown className="text-gray-400 shrink-0 ml-2" />
                          )}
                        </button>
                        {openModule === idx && (
                          <div className="p-4 bg-slate-50 text-xs sm:text-sm text-gray-600 border-t border-gray-100 leading-relaxed">
                            {faq?.answer || faq?.description}
                          </div>
                        )}
                      </div>
                    ))}
                  </div>
                </div>
              )}
            </div>
          </div>
        </section>
      </main>

      <Footer />

      {isModalOpen && (
        <FormModal
          isOpen={isModalOpen}
          onClose={() => setIsModalOpen(false)}
          courseTitle={courseTitle}
        />
      )}
    </>
  );
}
