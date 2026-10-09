import { useEffect } from "react";

export default function ShareThis() {
  useEffect(() => {
    const existingScript = document.getElementById("sharethis-script");

    if (!existingScript) {
      const script = document.createElement("script");
      script.id = "sharethis-script";
      script.type = "text/javascript";
      script.src =
        "https://platform-api.sharethis.com/js/sharethis.js#property=69a4166a6dc05e424818582b&product=sop";
      script.async = true;

      document.body.appendChild(script);
    } else if (window.__sharethis__) {
      window.__sharethis__.initialize();
    }
  }, []);

  return (
    <div className="sharethis-inline-share-buttons"></div>
  );
}