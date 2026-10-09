import React, { useEffect, useState, useMemo } from 'react';
import Head from 'next/head';
import Link from 'next/link';
import Navbar from '@/Components/Navbar';
import Footer from '@/Components/Footer';
import Loader from '@/Components/loader';
import FormModal from '@/Components/FormModal';
import { DynamicSEO, generateSupportPageSchema } from "@/lib/seoHelper";
import { API_BASE_URL } from '../../apiconfig';
import {
  FaChevronDown,
  FaChevronUp,
  FaCheckCircle,
  FaBook,
  FaHome,
  FaDownload,
  FaGraduationCap,
  FaRegClock,
  FaStar,
  FaAward,
} from 'react-icons/fa';
import { IoIosArrowForward } from 'react-icons/io';
import { MdOutlineSchool, MdOutlineMenuBook, MdVerified } from 'react-icons/md';

const safeHtml = (html) => {
  if (typeof window === 'undefined') return html || '';
  try {
    const DOMPurify = require('dompurify');
    return DOMPurify.sanitize(html || '');
  } catch (e) {
    return html || '';
  }
};

const normalizeImageUrl = (path, fallback = '/assets/landingpage/aboutus_bg.jpg') => {
  if (!path) return fallback;
  if (path.startsWith('http://') || path.startsWith('https://')) return path;
  const cleanPath = path.startsWith('/') ? path : `/${path}`;
  const base = (API_BASE_URL || '').replace(/\/+$/, '');
  return base ? `${base}${cleanPath}` : cleanPath;
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
      courseData?.course?.name ||
      courseData?.courseHeading?.title ||
      courseData?.dynamicCourseHeading?.heading ||
      (url_title ? url_title.replace(/-/g, ' ').toUpperCase() : `Course ${pageLabel}`),
    [courseData, url_title, pageLabel]
  );

  const courseDescription = useMemo(
    () =>
      courseData?.seo?.description ||
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
      courseData?.courseImage?.banner_image_path ||
      courseData?.certificationImage?.image_path ||
      courseData?.dynamicCourseHeading?.header_image;
    return normalizeImageUrl(raw);
  }, [courseData]);

  const certificateImage = useMemo(() => {
    const raw =
      courseData?.certificationImage?.image_path ||
      courseData?.courseImage?.banner_image_path;
    return normalizeImageUrl(raw, '/assets/images/new-dynamic-course/certificate.jpg');
  }, [courseData]);

  const faqs = useMemo(() => {
    return Array.isArray(courseData?.faqs) ? courseData.faqs : [];
  }, [courseData]);

  const courseSlug = url_title || '';
  const mainCourseUrl = `/${courseSlug}`;

  const canonicalPath = `/${courseSlug}/${pageType}`;
  const seoTitle = courseData?.seo?.title || `${courseTitle} - Complete ${pageLabel} & Curriculum | ScholarAcad`;
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

      <main className="min-h-screen bg-[#f8fafc] text-gray-800">
        {/* Breadcrumb Header */}
        <div className="bg-white border-b border-gray-200">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
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
                        <span className="text-blue-600 font-semibold">{b.name}</span>
                      ) : (
                        <Link
                          href={idx === 0 ? '/' : cleanedUrl}
                          className="hover:text-blue-600 transition truncate max-w-[200px]"
                        >
                          {idx === 0 ? (
                            <span className="flex items-center">
                              <FaHome className="mr-1 text-sm" /> {b.name}
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
                  className="flex items-center hover:text-blue-600 transition"
                >
                  <FaHome className="mr-1 text-sm" /> Home
                </Link>
                <IoIosArrowForward className="text-gray-400 text-xs shrink-0" />
                <Link
                  href={mainCourseUrl}
                  className="hover:text-blue-600 transition capitalize truncate max-w-[200px]"
                >
                  {courseTitle}
                </Link>
                <IoIosArrowForward className="text-gray-400 text-xs shrink-0" />
                <span className="text-blue-600 font-semibold">{pageLabel}</span>
              </nav>
            )}
          </div>
        </div>

        {/* Hero Section */}
        <section className="bg-gradient-to-r from-[#491689] via-[#6520b8] to-[#7f26d9] text-white py-12 lg:py-16 relative overflow-hidden shadow-md">
          <div className="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
              {/* Left text */}
              <div className="lg:col-span-7">
                <div className="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/15 text-purple-100 border border-white/25 text-xs font-semibold mb-4 backdrop-blur-sm">
                  <MdOutlineSchool className="text-sm" />
                  Comprehensive Curriculum & Modules
                </div>
                <h1 className="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight">
                  {courseTitle}{' '}
                  <span className="text-transparent bg-clip-text bg-gradient-to-r from-purple-200 to-pink-200">
                    Syllabus
                  </span>
                </h1>
                <p className="mt-4 text-base sm:text-lg text-purple-100/90 leading-relaxed line-clamp-3">
                  {courseDescription ||
                    'Explore the comprehensive syllabus, core modules, practical skills, and certification exam details designed to fast-track your career.'}
                </p>

                {/* Badges / Highlights */}
                <div className="mt-6 flex flex-wrap items-center gap-4 text-xs sm:text-sm text-gray-300">
                  <div className="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-sm">
                    <FaAward className="text-yellow-400" />
                    <span>Industry Recognized</span>
                  </div>
                  <div className="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-sm">
                    <MdVerified className="text-cyan-400" />
                    <span>100% Updated Curriculum</span>
                  </div>
                  <div className="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-sm">
                    <FaStar className="text-amber-400" />
                    <span>4.9/5 Rating</span>
                  </div>
                </div>

                {/* Action Buttons */}
                <div className="mt-8 flex flex-wrap gap-4">
                  {pdfUrl && (
                    <a
                      href={pdfUrl}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold text-sm shadow-lg hover:shadow-orange-500/30 transition transform hover:-translate-y-0.5"
                    >
                      <FaDownload /> Download Syllabus PDF
                    </a>
                  )}
                  <button
                    onClick={() => setIsModalOpen(true)}
                    className="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-lg hover:shadow-blue-500/30 transition transform hover:-translate-y-0.5"
                  >
                    <MdOutlineMenuBook /> Request Curriculum
                  </button>
                  <Link
                    href={mainCourseUrl}
                    className="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white/15 hover:bg-white/25 text-white font-semibold text-sm backdrop-blur-sm transition"
                  >
                    Course Details
                  </Link>
                </div>
              </div>

              {/* Right Course Image Card */}
              <div className="lg:col-span-5 flex justify-center lg:justify-end">
                <div className="relative w-full max-w-[420px] rounded-2xl overflow-hidden shadow-2xl border-2 border-white/20 bg-slate-900/60 backdrop-blur-md p-3 group">
                  <div className="overflow-hidden rounded-xl bg-slate-800 flex items-center justify-center min-h-[220px]">
                    <img
                      src={courseImage}
                      alt={courseTitle}
                      onError={(e) => {
                        e.currentTarget.src = '/assets/landingpage/aboutus_bg.jpg';
                      }}
                      className="w-full h-56 sm:h-64 object-cover group-hover:scale-105 transition-transform duration-500"
                    />
                  </div>
                  <div className="p-3 text-center">
                    <span className="text-xs font-semibold text-cyan-300 tracking-wide uppercase">
                      Official Accredited Certification
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* Content Section */}
        <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {/* Left 2 Cols: Curriculum & What You'll Learn */}
            <div className="lg:col-span-2 space-y-8">
              {/* What you'll learn */}
              {pointers && pointers.length > 0 && (
                <div className="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-100">
                  <h2 className="text-xl sm:text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <FaGraduationCap className="text-blue-600" />
                    What You Will Master in This Course
                  </h2>
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {pointers.map((item, idx) => (
                      <div
                        key={idx}
                        className="flex items-start gap-3 p-3.5 rounded-xl bg-slate-50 border border-slate-100 hover:border-blue-200 transition"
                      >
                        <FaCheckCircle className="text-green-500 mt-1 shrink-0 text-base" />
                        <span className="text-sm font-medium text-gray-700 leading-snug">
                          {typeof item === 'string' ? item : item?.title || JSON.stringify(item)}
                        </span>
                      </div>
                    ))}
                  </div>
                </div>
              )}

              {/* Detailed Syllabus / Modules Content */}
              <div className="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-100">
                <div className="flex flex-wrap items-center justify-between gap-4 mb-6 pb-4 border-b border-gray-100">
                  <div>
                    <h2 className="text-xl sm:text-2xl font-bold text-gray-900 flex items-center gap-2">
                      <FaBook className="text-blue-600" />
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
                      className="text-xs font-semibold text-blue-600 hover:text-blue-800 flex items-center gap-1.5"
                    >
                      <FaDownload /> Download PDF
                    </a>
                  )}
                </div>

                {curriculumHtml ? (
                  <div
                    className="prose max-w-none prose-headings:font-bold prose-headings:text-gray-900 prose-p:text-gray-700 prose-li:text-gray-700 prose-blue"
                    dangerouslySetInnerHTML={{
                      __html: isMounted ? safeHtml(curriculumHtml) : curriculumHtml,
                    }}
                  />
                ) : (
                  <div className="space-y-4">
                    <div className="p-4 rounded-xl border border-gray-200 bg-gray-50">
                      <h3 className="font-semibold text-gray-900 text-base mb-1">
                        Module 1: Introduction & Core Fundamentals
                      </h3>
                      <p className="text-sm text-gray-600">
                        Understanding key concepts, principles, frameworks, and foundational methodologies.
                      </p>
                    </div>
                    <div className="p-4 rounded-xl border border-gray-200 bg-gray-50">
                      <h3 className="font-semibold text-gray-900 text-base mb-1">
                        Module 2: In-Depth Methodologies & Practical Applications
                      </h3>
                      <p className="text-sm text-gray-600">
                        Real-world case studies, scenario-based learning, processes, and role assignments.
                      </p>
                    </div>
                    <div className="p-4 rounded-xl border border-gray-200 bg-gray-50">
                      <h3 className="font-semibold text-gray-900 text-base mb-1">
                        Module 3: Advanced Topics & Exam Preparation
                      </h3>
                      <p className="text-sm text-gray-600">
                        Mock exams, revision question banks, time management strategies, and final certification tips.
                      </p>
                    </div>
                  </div>
                )}
              </div>

              {/* Certificate Preview Card */}
              {certificateImage && (
                <div className="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-100 flex flex-col md:flex-row items-center gap-6">
                  <div className="w-full md:w-1/2 rounded-xl overflow-hidden border border-gray-200 bg-slate-50 p-2">
                    <img
                      src={certificateImage}
                      alt="Certificate Preview"
                      onError={(e) => {
                        e.currentTarget.src = '/assets/images/new-dynamic-course/certificate.jpg';
                      }}
                      className="w-full h-auto object-contain rounded-lg"
                    />
                  </div>
                  <div className="w-full md:w-1/2 space-y-3">
                    <h3 className="text-xl font-bold text-gray-900">
                      Official Global Certificate
                    </h3>
                    <p className="text-sm text-gray-600 leading-relaxed">
                      Upon completing the syllabus modules and clearing the evaluation, you will receive an industry-recognized certification to boost your career.
                    </p>
                    <div className="pt-2">
                      <button
                        onClick={() => setIsModalOpen(true)}
                        className="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow transition"
                      >
                        Enquire for Certificate
                      </button>
                    </div>
                  </div>
                </div>
              )}

              {/* FAQs if available */}
              {faqs.length > 0 && (
                <div className="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-100">
                  <h2 className="text-xl sm:text-2xl font-bold text-gray-900 mb-6">
                    Frequently Asked Questions (Syllabus & Exams)
                  </h2>
                  <div className="space-y-4">
                    {faqs.map((faq, idx) => (
                      <div
                        key={idx}
                        className="border border-gray-200 rounded-xl overflow-hidden"
                      >
                        <button
                          onClick={() => setOpenModule(openModule === idx ? -1 : idx)}
                          className="w-full flex items-center justify-between p-4 text-left font-semibold text-gray-800 hover:bg-gray-50 transition"
                        >
                          <span>{faq?.question || faq?.title}</span>
                          {openModule === idx ? (
                            <FaChevronUp className="text-blue-600 shrink-0 ml-2" />
                          ) : (
                            <FaChevronDown className="text-gray-400 shrink-0 ml-2" />
                          )}
                        </button>
                        {openModule === idx && (
                          <div className="p-4 bg-slate-50 text-sm text-gray-600 border-t border-gray-100">
                            {faq?.answer || faq?.description}
                          </div>
                        )}
                      </div>
                    ))}
                  </div>
                </div>
              )}
            </div>

            {/* Right 1 Col: Quick Info Sidebar & Lead Form */}
            <div className="space-y-6">
              {/* Quick Info Card */}
              <div className="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 sticky top-24">
                <h3 className="text-lg font-bold text-gray-900 mb-4 pb-3 border-b border-gray-100">
                  Course Summary
                </h3>
                <ul className="space-y-3.5 text-sm">
                  <li className="flex items-center justify-between text-gray-600">
                    <span className="flex items-center gap-2">
                      <FaRegClock className="text-blue-500" /> Mode
                    </span>
                    <span className="font-semibold text-gray-800">
                      Live Instructor-Led
                    </span>
                  </li>
                  <li className="flex items-center justify-between text-gray-600">
                    <span className="flex items-center gap-2">
                      <MdOutlineMenuBook className="text-blue-500" /> Access
                    </span>
                    <span className="font-semibold text-gray-800">
                      Lifetime LMS Access
                    </span>
                  </li>
                  <li className="flex items-center justify-between text-gray-600">
                    <span className="flex items-center gap-2">
                      <FaAward className="text-blue-500" /> Certificate
                    </span>
                    <span className="font-semibold text-gray-800">
                      Official Accredited
                    </span>
                  </li>
                  <li className="flex items-center justify-between text-gray-600">
                    <span className="flex items-center gap-2">
                      <MdVerified className="text-blue-500" /> Passing Rate
                    </span>
                    <span className="font-semibold text-green-600">99.2%</span>
                  </li>
                </ul>

                <div className="mt-6 pt-4 border-t border-gray-100 space-y-3">
                  <button
                    onClick={() => setIsModalOpen(true)}
                    className="w-full py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm text-center shadow-md hover:shadow-blue-500/20 transition"
                  >
                    Enquire for Next Batch
                  </button>
                  <Link
                    href={mainCourseUrl}
                    className="block w-full py-2.5 px-4 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm text-center transition"
                  >
                    Back to Course Details
                  </Link>
                </div>
              </div>
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
