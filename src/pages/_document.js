import { Html, Head, Main, NextScript } from "next/document";

export default function Document() {
  return (
    <Html lang="en">
      <Head>
        <link rel="icon" href="/assets/landingpage/scholaracad_favicon.svg" />
        <meta
          name="google-site-verification"
          content="DshteP3dbdQe9JBoX-QXsB0d6_msIpsNXs13X2MgqQM"
        />
        <meta name="robots" content="index, follow" />
      </Head>
      <body className="antialiased">
        <Main />
        <NextScript />
      </body>
    </Html>
  );
}
