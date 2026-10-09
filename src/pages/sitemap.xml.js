import { API_BASE_URL, APIENDPOINTS } from "../../apiconfig";

function generateSitemap(dynamicXml) {
  return `<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
${dynamicXml}
</urlset>`;
}
export async function getServerSideProps({ req, res }) {
  function normalizeUrl(url) {
    try {
      const u = new URL(url);
      const decodedPath = decodeURIComponent(u.pathname);
      const normalizedPath = decodedPath
        .split("/")
        .map((segment) => segment.trim().toLowerCase().replace(/\s+/g, "-"))
        .join("/");
      return `${u.origin}${normalizedPath}`;
    } catch {
      return url;
    }
  }
  const response = await fetch(`${API_BASE_URL}${APIENDPOINTS.SITEMAP_URL}`, {
    method: "GET",
    headers: {
      Accept: "application/json",
    },
  });
  const apiData = await response.json();
  if (!Array.isArray(apiData?.data)) {
    throw new Error("API did not return array");
  }
  const dynamicUrlsXml = apiData.data
    .map(
      (item) => `
  <url>
    <loc>${normalizeUrl(item?.url)}</loc>
    <lastmod>${
      item?.updated_at
        ? new Date(item.updated_at).toISOString()
        : new Date().toISOString()
    }</lastmod>
    <changefreq>daily</changefreq>
    <priority>1.0</priority>
  </url>
`,
    )
    .join("");
  const sitemap = generateSitemap(dynamicUrlsXml);
  res.setHeader("Content-Type", "application/xml");
  res.write(sitemap);
  res.end();

  return { props: {} };
}

export default function Sitemap() {
  return null;
}
