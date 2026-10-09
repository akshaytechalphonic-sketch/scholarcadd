module.exports = {
  async redirects() {
    return [
      {
        source: '/:path*',
        has: [
          {
            type: 'host',
            value: 'www.scholaracad.com',
          },
        ],
        destination: 'https://scholaracad.com/:path*',
        permanent: true,
      },
    ]
  },
}
