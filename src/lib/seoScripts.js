import Script from "next/script";

/* -------------------- Google Analytics -------------------- */
export function GoogleAnalytics() {
  return (
    <>
      <Script
        strategy="afterInteractive"
        src="https://www.googletagmanager.com/gtag/js?id=G-GXX5F8KF19"
      />
      <Script
        id="google-analytics"
        strategy="afterInteractive"
        dangerouslySetInnerHTML={{
          __html: `
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'G-GXX5F8KF19');
          `,
        }}
      />
    </>
  );
}

/* -------------------- Google Tag Manager -------------------- */
export function GoogleTagManager() {
  return (
    <Script
      type="application/ld+json"
      dangerouslySetInnerHTML={{
        __html: JSON.stringify({
          "@context": "https://schema.org",
          "@type": "Organization",
          name: "ScholarAcad",
          url: "https://scholaracad.com/",
          logo: "https://scholaracad.com/assets/images/new_logo.png",
          contactPoint: {
            "@type": "ContactPoint",
            telephone: "+91 9810812106",
            contactType: "customer service",
            contactOption: "TollFree",
            areaServed: [
              "US",
              "GB",
              "CA",
              "AF",
              "AX",
              "AL",
              "AS",
              "DZ",
              "AD",
              "AO",
              "AI",
              "AR",
              "AG",
              "AM",
              "AW",
              "AU",
              "AT",
              "AZ",
              "BH",
              "BS",
              "HR",
              "CR",
              "CK",
              "KM",
              "IN",
              "IS",
              "HU",
              "HK",
              "JE",
              "JP",
              "KZ",
              "JO",
            ],
            availableLanguage: [
              "en",
              "es",
              "fr",
              "Afar",
              "Abkhazian",
              "Avestan",
              "Assamese",
              "Arabic",
              "Avaric",
              "Aymara",
              "Azerbaijani",
              "Bambara",
              "Bislama",
              "Bengali",
              "Tibetan",
              "Cree",
              "Corsican",
              "Chamorro",
              "Welsh",
              "Chuvash",
              "Church Slavic",
              "Basque",
              "Esperanto",
              "Estonian",
              "Greek",
              "Ewe",
              "Dzongkha",
              "Japanese",
              "Javanese",
              "Georgian",
              "Kongo",
            ],
          },
          sameAs: [
            "https://www.facebook.com/people/Scholaracadcom/100075563031863/",
            "https://x.com/ScholarAcad",
            "https://www.instagram.com/scholaracadtrng/",
            "https://www.youtube.com/@scholaracad",
            "https://www.linkedin.com/company/scholaracad/?viewAsMember=true",
            "https://scholaracad.com/",
          ],
        }),
      }}
    />
  );
}

/* -------------------- Website Schema -------------------- */
export function WebsiteSchema() {
  return (
    <Script
      id="website-schema"
      type="application/ld+json"
      strategy="beforeInteractive"
      dangerouslySetInnerHTML={{
        __html: JSON.stringify({
          "@context": "https://schema.org",
          "@type": "WebSite",
          "@id": "https://scholaracad.com/#website",
          name: "ScholarAcad",
          url: "https://scholaracad.com/",
          potentialAction: {
            "@type": "SearchAction",
            target: {
              "@type": "EntryPoint",
              urlTemplate:
                "https://scholaracad.com/search?q={search_term_string}",
            },
            "query-input": "required name=search_term_string",
          },
        }),
      }}
    />
  );
}
