import React from "react";
import { useRouter } from "next/router";
import DedicatedQuizQuestionsPage from "@/Components/DedicatedQuizQuestionsPage";
import { API_BASE_URL } from "../../../../../apiconfig";

export default function CourseQuizQuestionsPage({
  initialData,
  courseSlug,
  quizSlug,
}) {
  const router = useRouter();
  const targetCourseSlug = courseSlug || router.query.courseSlug;
  const targetQuizSlug = quizSlug || router.query.quizSlug;

  return (
    <DedicatedQuizQuestionsPage
      courseSlug={targetCourseSlug}
      quizSlug={targetQuizSlug}
      initialData={initialData}
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
      `${API_BASE_URL}/api/courses/${courseSlug}/quizzes/${quizSlug}/questions`,
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
          initialData: null,
        },
      };
    }

    const json = await res.json();
    const quizTitle = json?.data?.quiz?.title || "Exam Simulator";
    const courseName = json?.data?.course?.name || courseSlug;

    return {
      props: {
        title: `${quizTitle} — Questions | ScholarAcad`,
        description: `Interactive question screen for ${quizTitle} - ${courseName}`,
        courseSlug,
        quizSlug,
        initialData: json?.data || null,
      },
    };
  } catch (e) {
    return {
      props: {
        courseSlug,
        quizSlug,
        initialData: null,
      },
    };
  }
}
