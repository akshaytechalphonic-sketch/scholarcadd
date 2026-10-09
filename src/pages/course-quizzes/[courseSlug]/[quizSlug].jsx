import React from "react";
import { useRouter } from "next/router";
import DedicatedQuizDetailPage from "@/Components/DedicatedQuizDetailPage";
import { API_BASE_URL } from "../../../../apiconfig";

export default function CourseQuizDetailPage({
  initialQuizData,
  courseSlug,
  quizSlug,
}) {
  const router = useRouter();
  const targetCourseSlug = courseSlug || router.query.courseSlug;
  const targetQuizSlug = quizSlug || router.query.quizSlug;

  return (
    <DedicatedQuizDetailPage
      courseSlug={targetCourseSlug}
      quizSlug={targetQuizSlug}
      initialQuizData={initialQuizData}
      url_title={targetCourseSlug}
    />
  );
}

export async function getServerSideProps({ req, params }) {
  const token = req.cookies?.access_token || null;
  const courseSlug = params?.courseSlug || "";
  const quizSlug = params?.quizSlug || "";

  try {
    const res = await fetch(
      `${API_BASE_URL}/api/courses/${courseSlug}/quizzes/${quizSlug}`,
      {
        method: "GET",
        headers: {
          Accept: "application/json",
          ...(token ? { Authorization: `Bearer ${token}` } : {}),
        },
      }
    );

    if (!res.ok) {
      return {
        props: {
          courseSlug,
          quizSlug,
          initialQuizData: null,
        },
      };
    }

    const json = await res.json();
    const quizTitle = json?.data?.quiz?.title || "Quiz Details";
    const courseName = json?.data?.course?.name || courseSlug;

    return {
      props: {
        title: `${quizTitle} — ${courseName} | ScholarAcad`,
        description:
          json?.data?.quiz?.description ||
          `Practice ${quizTitle} for ${courseName} at ScholarAcad.`,
        keywords: `${quizTitle}, ${courseName}, mock exam`,
        ogTitle: `${quizTitle} — ${courseName} | ScholarAcad`,
        ogDescription:
          json?.data?.quiz?.description ||
          `Practice ${quizTitle} for ${courseName} at ScholarAcad.`,
        courseSlug,
        quizSlug,
        initialQuizData: json?.data || null,
      },
    };
  } catch (e) {
    return {
      props: {
        title: "Quiz Details | ScholarAcad",
        description: "Quiz details and instructions.",
        keywords: null,
        ogTitle: "Quiz Details | ScholarAcad",
        ogDescription: "Quiz details and instructions.",
        courseSlug,
        quizSlug,
        initialQuizData: null,
      },
    };
  }
}
