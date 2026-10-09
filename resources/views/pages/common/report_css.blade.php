  <style>
      p {
          font-size: 12px;
          font-weight: normal
      }

      .info-grid {
          display: grid;
          grid-template-columns: auto 1fr;
          gap: 0.75rem 1rem;
          max-width: 100%;
      }

      @media (min-width: 768px) {
          .info-grid {
              grid-template-columns: repeat(4, 1fr);
          }

          .info-grid>div:nth-child(4n-3),
          .info-grid>div:nth-child(4n-2) {
              grid-column: span 1;
          }
      }

      @media print {
          body * {
              visibility: hidden;
          }

          #report,
          #report * {
              visibility: visible;
          }

          #report {
              position: absolute;
              left: 0;
              top: 0;
              width: 100%;
          }
      }

      #report table th{
         padding: 8px !important;
          font-size: 13px;
          border: 1px solid #ccc;
      }
      #report table td {
          padding: 8px !important;
          font-size: 13px;
          /* border: 1px solid #ccc; */
      }

      #report h1,
      #report h2,
      #report h3 {
          padding: 8px 0;
          margin: 0;
          font-weight: bold;
          text-align: center;
      }

      #report .section-title {
          background-color: #1F3F49;
          color: white;
          padding: 6px;
          margin-bottom: 10px;
          border-radius: 2px;
      }
  </style>
