import React from "react";
import { useRouter } from "next/router";
import DedicatedQuizzesPage from "@/Components/DedicatedQuizzesPage";
import { API_BASE_URL } from "../../../apiconfig";

export default function CourseQuizzesPage({ initialQuizzes, courseData, courseSlug }) {
  const router = useRouter();
  const slug = courseSlug || router.query.courseSlug;

  return (
    <DedicatedQuizzesPage
      courseSlug={slug}
      initialQuizzes={initialQuizzes}
      courseData={courseData}
      url_title={slug}
    />
  );
}

export async function getServerSideProps({ req, params }) {
  const token = req.cookies?.access_token || null;
  const courseSlug = params?.courseSlug || "";

  try {
    const res = await fetch(`${API_BASE_URL}/api/courses/${courseSlug}/quizzes`, {
      method: "GET",
      headers: {
        Accept: "application/json",
        ...(token ? { Authorization: `Bearer ${token}` } : {}),
      },
    });

    if (!res.ok) {
      return {
        props: {
          courseSlug,
          initialQuizzes: [],
          courseData: null,
        },
      };
    }

    const data = await res.json();
    return {
      props: {
        courseSlug,
        initialQuizzes: Array.isArray(data?.data) ? data.data : [],
        courseData: data?.course || null,
      },
    };
  } catch (e) {
    return {
      props: {
        courseSlug,
        initialQuizzes: [],
        courseData: null,
      },
    };
  }
}
