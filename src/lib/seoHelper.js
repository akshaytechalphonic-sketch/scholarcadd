import Head from "next/head";
import { CLIENT_BASE_URL } from "../../apiconfig";

/**
 * Standardize base domain for canonical URLs and Schema IDs
 */
export const DEFAULT_SITE_URL = "https://scholaracad.com";

export function getBaseClientUrl() {
  if (typeof window !== "undefined" && window.location.origin) {
    return window.location.origin.replace(/\/+$/, "");
  }
  const configured = (CLIENT_BASE_URL || "").trim().replace(/\/+$/, "");
  return configured || DEFAULT_SITE_URL;
}

/**
 * Clean canonical URL generator
 * - Removes country prefixes like `/in/`
 * - Strips query parameters and URL hashes
 * - Resolves relative paths against the clean client URL
 */
export function getCleanCanonicalUrl(pathOrUrl = "") {
  const base = getBaseClientUrl();
  if (!pathOrUrl) return `${base}/`;

  let clean = String(pathOrUrl).trim();

  // If full URL with origin, extract path
  try {
    if (clean.startsWith("http://") || clean.startsWith("https://")) {
      const parsed = new URL(clean);
      clean = parsed.pathname;
    }
  } catch (e) {
    // fallback to string manipulation
  }

  // Strip query string and hashes
  clean = clean.split("?")[0].split("#")[0];

  // Remove leading/trailing slashes for processing
  clean = clean.replace(/^\/+/, "").replace(/\/+$/, "");

  // Remove /in/ prefix if present (e.g. "in/course/syllabus" -> "course/syllabus")
  if (clean.toLowerCase().startsWith("in/")) {
    clean = clean.slice(3);
  } else if (clean.toLowerCase() === "in") {
    clean = "";
  }

  // Remove double slashes
  clean = clean.replace(/\/+/g, "/");

  return clean ? `${base}/${clean}` : `${base}/`;
}

/**
 * Helper to strip HTML tags for clean schema descriptions
 */
export function stripHtml(html = "") {
  if (!html) return "";
  return String(html)
    .replace(/<[^>]*>?/gm, "")
    .replace(/\s+/g, " ")
    .trim();
}

/**
 * Generates Schema.org BreadcrumbList
 */
export function generateBreadcrumbSchema(items = [], pageUrl = "") {
  const base = getBaseClientUrl();
  const list = Array.isArray(items) && items.length > 0 ? items : [
    { name: "Home", url: `${base}/` },
    { name: "Page", url: pageUrl || `${base}/` }
  ];

  return {
    "@type": "BreadcrumbList",
    "@id": `${pageUrl || base}/#breadcrumb`,
    "itemListElement": list.map((item, index) => ({
      "@type": "ListItem",
      "position": index + 1,
      "name": item.name,
      "item": getCleanCanonicalUrl(item.url || "")
    }))
  };
}

/**
 * Generates Schema.org EducationalOrganization
 */
export function generateOrganizationSchema() {
  const base = getBaseClientUrl();
  return {
    "@type": "EducationalOrganization",
    "@id": `${base}/#organization`,
    "name": "ScholarAcad",
    "url": `${base}/`,
    "logo": `${base}/assets/images/new_logo.png`,
    "description": "ScholarAcad is a premier globally accredited professional certification and corporate training provider.",
    "contactPoint": {
      "@type": "ContactPoint",
      "telephone": "+91 9810812106",
      "contactType": "Customer Support",
      "availableLanguage": ["English", "Hindi"]
    },
    "sameAs": [
      "https://www.facebook.com/people/Scholaracadcom/100075563031863/",
      "https://x.com/ScholarAcad",
      "https://www.instagram.com/scholaracadtrng/",
      "https://www.youtube.com/@scholaracad",
      "https://www.linkedin.com/company/scholaracad/?viewAsMember=true"
    ]
  };
}

/**
 * Generates Schema.org FAQPage (only when real FAQ array with non-empty Q&A exists)
 */
export function generateFAQSchema(faqs = []) {
  if (!Array.isArray(faqs) || faqs.length === 0) return null;

  const validQuestions = faqs
    .map((f) => {
      const question = f.question || f.title || f.faq_title;
      const answer = stripHtml(f.answer || f.description || f.faq_description || "");
      if (!question || !answer) return null;
      return {
        "@type": "Question",
        "name": String(question).trim(),
        "acceptedAnswer": {
          "@type": "Answer",
          "text": answer
        }
      };
    })
    .filter(Boolean);

  if (validQuestions.length === 0) return null;

  return {
    "@type": "FAQPage",
    "mainEntity": validQuestions
  };
}

/**
 * Generates Complete Course Schema (@graph with Course, EducationalOrganization, Breadcrumbs, and conditional FAQs/Reviews/Offers)
 */
export function generateCourseSchema({
  courseName,
  courseDescription,
  courseUrl,
  imageUrl,
  breadcrumbs = [],
  faqs = [],
  reviews = [],
  ratings = null,
  pricing = null,
  providerName = "ScholarAcad"
}) {
  const base = getBaseClientUrl();
  const cleanUrl = getCleanCanonicalUrl(courseUrl);
  const cleanDesc = stripHtml(courseDescription) || `${courseName} Professional Certification Training at ${providerName}.`;

  const courseObj = {
    "@type": "Course",
    "@id": `${cleanUrl}/#course`,
    "name": courseName,
    "description": cleanDesc,
    "url": cleanUrl,
    "image": imageUrl || `${base}/assets/landingpage/aboutus_bg.jpg`,
    "inLanguage": "en",
    "provider": {
      "@type": "EducationalOrganization",
      "name": providerName,
      "url": `${base}/`,
      "sameAs": `${base}/`
    }
  };

  // Only include AggregateRating if real, non-zero rating data is available
  const ratingValue = Number(ratings?.rating_value || ratings?.avg_rating || ratings?.rating || 0);
  const reviewCount = Number(ratings?.review_count || ratings?.total_reviews || ratings?.count || (Array.isArray(reviews) ? reviews.length : 0));

  if (ratingValue > 0 && reviewCount > 0) {
    courseObj.aggregateRating = {
      "@type": "AggregateRating",
      "ratingValue": ratingValue > 5 ? (ratingValue / 20).toFixed(1) : ratingValue.toFixed(1),
      "bestRating": "5",
      "worstRating": "1",
      "ratingCount": reviewCount
    };
  }

  // Only include real, visible individual reviews if present
  if (Array.isArray(reviews) && reviews.length > 0) {
    const validReviews = reviews
      .slice(0, 5)
      .map((r) => {
        const authorName = r.name || r.author || r.user_name || r.student_name;
        const reviewText = stripHtml(r.review || r.comment || r.message || r.content || "");
        const reviewRating = Number(r.rating || r.score || 5);
        if (!authorName || !reviewText) return null;
        return {
          "@type": "Review",
          "author": {
            "@type": "Person",
            "name": authorName
          },
          "reviewBody": reviewText,
          "reviewRating": {
            "@type": "Rating",
            "ratingValue": reviewRating > 5 ? 5 : reviewRating,
            "bestRating": "5",
            "worstRating": "1"
          }
        };
      })
      .filter(Boolean);

    if (validReviews.length > 0) {
      courseObj.review = validReviews;
    }
  }

  // Only include Product & Offer if real, positive pricing data is present
  const priceAmount = Number(pricing?.amount || pricing?.price || pricing?.discounted_price || 0);
  const currencyCode = pricing?.currency || "INR";

  let productObj = null;
  if (priceAmount > 0) {
    courseObj.offers = {
      "@type": "Offer",
      "url": cleanUrl,
      "priceCurrency": currencyCode,
      "price": priceAmount,
      "priceValidUntil": new Date(new Date().setFullYear(new Date().getFullYear() + 1)).toISOString().split("T")[0],
      "availability": "https://schema.org/InStock",
      "seller": {
        "@type": "EducationalOrganization",
        "name": providerName
      }
    };

    productObj = {
      "@type": "Product",
      "@id": `${cleanUrl}/#product`,
      "name": courseName,
      "description": cleanDesc,
      "image": imageUrl || `${base}/assets/landingpage/aboutus_bg.jpg`,
      "offers": courseObj.offers
    };

    if (courseObj.aggregateRating) {
      productObj.aggregateRating = courseObj.aggregateRating;
    }
    if (courseObj.review) {
      productObj.review = courseObj.review;
    }
  }

  const graph = [
    generateOrganizationSchema(),
    generateBreadcrumbSchema(breadcrumbs, cleanUrl),
    courseObj
  ];

  if (productObj) {
    graph.push(productObj);
  }

  const faqSchema = generateFAQSchema(faqs);
  if (faqSchema) {
    graph.push(faqSchema);
  }

  return {
    "@context": "https://schema.org",
    "@graph": graph
  };
}

/**
 * Generates Schema.org ItemList for Course Listing / Category pages
 */
export function generateCourseListingSchema({
  title,
  description,
  pageUrl,
  courses = [],
  breadcrumbs = []
}) {
  const base = getBaseClientUrl();
  const cleanUrl = getCleanCanonicalUrl(pageUrl);

  const itemListElement = (Array.isArray(courses) ? courses : []).slice(0, 30).map((c, index) => {
    const courseTitle = c.name || c.course_title || c.title || "Course";
    const courseSlug = c.url_title || c.slug || "";
    const courseUrl = courseSlug ? getCleanCanonicalUrl(courseSlug) : cleanUrl;
    const desc = stripHtml(c.description || c.course_description || c.meta_description || "");

    return {
      "@type": "ListItem",
      "position": index + 1,
      "item": {
        "@type": "Course",
        "name": courseTitle,
        "description": desc || `${courseTitle} Certification Course at ScholarAcad.`,
        "url": courseUrl,
        "provider": {
          "@type": "EducationalOrganization",
          "name": "ScholarAcad",
          "url": `${base}/`
        }
      }
    };
  });

  return {
    "@context": "https://schema.org",
    "@graph": [
      generateOrganizationSchema(),
      generateBreadcrumbSchema(breadcrumbs, cleanUrl),
      {
        "@type": "ItemList",
        "@id": `${cleanUrl}/#itemlist`,
        "name": title,
        "description": stripHtml(description),
        "url": cleanUrl,
        "numberOfItems": itemListElement.length,
        "itemListElement": itemListElement
      }
    ]
  };
}

/**
 * Generates Schema.org WebPage for Support pages (Syllabus, Exam Format, Eligibility, Quizzes, Notes, etc.)
 */
export function generateSupportPageSchema({
  title,
  description,
  pageUrl,
  breadcrumbs = [],
  faqs = []
}) {
  const cleanUrl = getCleanCanonicalUrl(pageUrl);
  const cleanDesc = stripHtml(description);

  const graph = [
    generateOrganizationSchema(),
    generateBreadcrumbSchema(breadcrumbs, cleanUrl),
    {
      "@type": "WebPage",
      "@id": `${cleanUrl}/#webpage`,
      "name": title,
      "description": cleanDesc,
      "url": cleanUrl,
      "isPartOf": {
        "@type": "WebSite",
        "@id": `${getBaseClientUrl()}/#website`,
        "name": "ScholarAcad",
        "url": `${getBaseClientUrl()}/`
      }
    }
  ];

  const faqSchema = generateFAQSchema(faqs);
  if (faqSchema) {
    graph.push(faqSchema);
  }

  return {
    "@context": "https://schema.org",
    "@graph": graph
  };
}

/**
 * Unified Component: <DynamicSEO />
 * Injects Title, Meta Description, Canonical, Robots, OpenGraph, Twitter, and Schema JSON-LD
 */
export function DynamicSEO({
  title,
  description,
  keywords,
  canonicalUrl,
  ogType = "website",
  ogImage,
  robots = "index, follow",
  schemaData = null
}) {
  const base = getBaseClientUrl();
  const cleanCanonical = getCleanCanonicalUrl(canonicalUrl);
  const cleanTitle = title || "ScholarAcad | Professional Certification Training";
  const cleanDesc = stripHtml(description) || "ScholarAcad offers globally recognized certification training in PMP, Agile, ITIL, DevOps & IT Governance.";
  const defaultImage = `${base}/assets/landingpage/aboutus_bg.jpg`;
  const cleanImage = ogImage
    ? ogImage.startsWith("http")
      ? ogImage
      : `${base}${ogImage.startsWith("/") ? ogImage : `/${ogImage}`}`
    : defaultImage;

  return (
    <Head>
      {/* Primary Meta Tags */}
      <title key="title">{cleanTitle}</title>
      <meta key="description" name="description" content={cleanDesc} />
      {keywords && <meta key="keywords" name="keywords" content={keywords} />}
      <meta key="robots" name="robots" content={robots} />
      <link key="canonical" rel="canonical" href={cleanCanonical} />

      {/* Open Graph / Facebook */}
      <meta key="og:type" property="og:type" content={ogType} />
      <meta key="og:site_name" property="og:site_name" content="ScholarAcad" />
      <meta key="og:locale" property="og:locale" content="en_US" />
      <meta key="og:url" property="og:url" content={cleanCanonical} />
      <meta key="og:title" property="og:title" content={cleanTitle} />
      <meta key="og:description" property="og:description" content={cleanDesc} />
      <meta key="og:image" property="og:image" content={cleanImage} />

      {/* Twitter */}
      <meta key="twitter:card" name="twitter:card" content="summary_large_image" />
      <meta key="twitter:site" name="twitter:site" content="@ScholarAcad" />
      <meta key="twitter:url" name="twitter:url" content={cleanCanonical} />
      <meta key="twitter:title" name="twitter:title" content={cleanTitle} />
      <meta key="twitter:description" name="twitter:description" content={cleanDesc} />
      <meta key="twitter:image" name="twitter:image" content={cleanImage} />

      {/* Structured Data (JSON-LD) */}
      {schemaData && (
        <script
          key="structured-data"
          type="application/ld+json"
          dangerouslySetInnerHTML={{ __html: JSON.stringify(schemaData) }}
        />
      )}
    </Head>
  );
}
