// import { useEffect } from "react";
// const Chatbot = () => {
//   useEffect(() => {
//     if (window.Tawk_API) return;
//     window.Tawk_API = window.Tawk_API || {};
//     window.Tawk_LoadStart = new Date();
//     const script = document.createElement("script");
//     script.src = "https://embed.tawk.to/63e29e7247425128791207e0/1gomjlslu";
//     script.async = true;
//     script.charset = "UTF-8";
//     script.setAttribute("crossorigin", "*");
//     document.body.appendChild(script);
//     const originalTitle = document.title;
//     const titleObserver = new MutationObserver(() => {
//       if (document.title !== originalTitle) {
//         document.title = originalTitle;
//       }
//     });
//     titleObserver.observe(document.querySelector("title"), {
//       childList: true,
//     });
//     return () => {
//       document.body.removeChild(script);
//     };
//   }, []);
//   return null;
// };

// export default Chatbot;
import { useEffect } from "react";
import { useRouter } from "next/router";

const Chatbot = () => {
  const router = useRouter();
  useEffect(() => {
    if (window.Tawk_API) return;
    window.Tawk_API = window.Tawk_API || {};
    window.Tawk_LoadStart = new Date();
    const script = document.createElement("script");
    script.src = "https://embed.tawk.to/63e29e7247425128791207e0/1gomjlslu";
    script.async = true;
    script.charset = "UTF-8";
    script.setAttribute("crossorigin", "*");
    document.body.appendChild(script);
    return () => {
      document.body.removeChild(script);
    };
  }, []);
  useEffect(() => {
    let expectedTitle = document.title;
    const observer = new MutationObserver(() => {
      const currentTitle = document.title;
      if (
        currentTitle.includes("New Message") ||
        currentTitle.includes("Chat")
      ) {
        document.title = expectedTitle;
      } else {
        expectedTitle = currentTitle;
      }
    });
    const titleEl = document.querySelector("title");
    if (titleEl) {
      observer.observe(titleEl, { childList: true });
    }
    return () => observer.disconnect();
  }, [router.asPath]);
  return null;
};

export default Chatbot;