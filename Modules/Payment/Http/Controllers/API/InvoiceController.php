<?php

namespace Modules\Payment\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\Enrollment;
use App\Models\coursedate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function downloadInvoicePDF(Request $request)
    {
        try {
            // Get ID from form data/request body
            $request->validate([
                'id' => 'required|integer|exists:tbl_enrollments,id'
            ]);

            $id = $request->input('id');

            $enrollment = Enrollment::with(['course', 'plan'])
                ->findOrFail($id);

            // Create invoice items array - FIXED: Use enrollment->quantity
            $invoiceItems = collect([[
                'course_name' => $enrollment->course->course_title ?? 'Course',
                'qty' => $enrollment->quantity ?? 1,
                'amount' => $enrollment->paid_amount ?? 0,
                'total' => ($enrollment->paid_amount ?? 0) * ($enrollment->quantity ?? 1)
            ]]);

            // Calculate total - FIXED: Use actual total calculation
            $total = ($enrollment->paid_amount ?? 0) * ($enrollment->quantity ?? 1);

            // Convert amount to words
            $amountInWords = $this->numberToWords($total);

            // Generate HTML for PDF (EXACT SAME FORMAT)
            $html = $this->generateInvoiceHTML($enrollment, $invoiceItems, $total, $amountInWords);

            // Generate PDF
            $pdf = Pdf::loadHTML($html)
                ->setPaper('a4')
                ->setOptions([
                    'margin-top' => 10,
                    'margin-bottom' => 10,
                    'margin-left' => 10,
                    'margin-right' => 10,
                    'isRemoteEnabled' => true
                ]);

            // Download PDF
            return $pdf->download('Invoice_' . ($enrollment->invoice_no ?? 'ENR-' . $id) . '.pdf');

        } catch (\Exception $e) {
            return response()->json(['error' => 'Invoice generation failed: ' . $e->getMessage()], 500);
        }
    }

    private function generateInvoiceHTML($enrollment, $invoiceItems, $total, $amountInWords): string
    {
        $itemsHTML = '';
        foreach ($invoiceItems as $item) {
            $itemsHTML .= '
            <tr>
                <td class="border p-1">' . htmlspecialchars($item['course_name']) . '</td>
                <td class="border p-1 text-center">' . $item['qty'] . '</td>
                <td class="border p-1 text-right">₹' . number_format($item['amount'], 0) . '</td>
                <td class="border p-1 text-right">₹' . number_format($item['total'], 0) . '</td>
            </tr>';
        }

        // Full customer name
        $fullName = trim(($enrollment->first_name ?? '') . ' ' . ($enrollment->last_name ?? ''));

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Invoice - ScholarAcad</title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; font-size: 14px; color: #000; line-height: 1.4; }
                .container { max-width: 800px; margin: 0 auto; padding: 24px; background: white; }
                .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #000; margin-bottom: 24px; padding-bottom: 16px; }
                .logo img { height: 48px; width: auto; object-fit: contain; }
                .invoice-title { text-align: right; font-weight: bold; font-size: 24px; margin-right: 20px; }
                .content-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 24px; }
                .company-info p { margin: 4px 0; }
                .company-name { font-weight: bold; font-size: 16px; margin-bottom: 8px; }
                .detail-row { display: grid; grid-template-columns: 140px 1fr; margin-bottom: 8px; }
                .detail-label { font-weight: normal; }
                .detail-value { text-align: right; padding-right: 20px; }
                .description-heading { font-weight: 600; margin: 24px 0 8px 0; font-size: 15px; }
                table { width: 100%; border-collapse: collapse; border: 1px solid #000; margin-bottom: 24px; font-size: 13px; }
                th, td { border: 1px solid #000; padding: 8px 4px; }
                th { background: #f5f5f5; font-weight: bold; }
                th.text-left { text-align: left; }
                th.text-center { text-align: center; width: 60px; }
                th.text-right { text-align: right; width: 100px; }
                td.text-center { text-align: center; }
                td.text-right { text-align: right; }
                .total-section { margin-bottom: 16px; }
                .total-row { display: grid; grid-template-columns: 3fr 1fr; align-items: center; padding: 12px 0; border-top: 2px solid #000; }
                .total-label { text-align: right; padding-right: 12px; font-weight: bold; font-size: 16px; }
                .total-value { text-align: right; padding-right: 12px; font-weight: bold; font-size: 18px; }
                .amount-in-words { display: flex; align-items: center; margin-bottom: 24px; gap: 20px; }
                .amount-in-words-label { font-weight: bold; width: 180px; flex-shrink: 0; }
                .amount-in-words-value { flex: 1; text-align: right; font-weight: 600; padding-right: 20px; font-size: 15px; }
                .plan-details { display: grid; grid-template-columns: 1fr 1fr; gap: 8px 20px; margin-bottom: 32px; border: 1px solid #000; padding: 16px; background: #f9f9f9; }
                .plan-detail-item { display: flex; justify-content: space-between; padding: 4px 0; }
                .plan-detail-label { font-weight: 600; color: #333; }
                .plan-detail-value { text-align: right; font-weight: 500; }
                .footer { text-align: center; margin-top: 32px; padding-top: 24px; border-top: 2px solid #ddd; }
                .thank-you { font-weight: bold; font-size: 16px; margin-bottom: 12px; color: #2c5aa0; }
                .footer-note { font-size: 12px; color: #666; line-height: 1.5; }
                @media print { body { -webkit-print-color-adjust: exact; print-color-adjust: exact; } }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <div class="logo">
                        <img src="https://scholaracad-uatadmin.kefify.com/assets/image/logo/scholaracad_logo.svg" alt="ScholarAcad Logo">
                    </div>
                    <div class="invoice-title">Invoice</div>
                </div>

                <div class="content-grid">
                    <div class="company-info">
                        <p class="company-name">Scholar Academy Consulting</p>
                        <p>A-43, Trikuta Hills, Star Sehkari Awas Samiti Ltd.</p>
                        <p>Plot No. C-58/27, Noida Sector-62</p>
                        <p>Gautam Buddha Nagar, Uttar Pradesh, 201309</p>
                        <p style="margin-top: 12px;"><strong>GSTIN:</strong> 09BLSPP5713P1Z3</p>
                    </div>

                    <div class="invoice-details">
                        <div class="detail-row">
                            <span class="detail-label">Invoice No</span>
                            <span class="detail-value">' . htmlspecialchars($enrollment->invoice_no ?? 'ENR-' . $enrollment->id) . '</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Date</span>
                            <span class="detail-value">' . ($enrollment->payment_date ? date('d-m-Y', strtotime($enrollment->payment_date)) : date('d-m-Y')) . '</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Customer Name</span>
                            <span class="detail-value">' . htmlspecialchars($fullName) . '</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Contact Number</span>
                            <span class="detail-value">' . htmlspecialchars(($enrollment->country_code ?? '+91') . ' ' . ($enrollment->phone ?? '')) . '</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Email</span>
                            <span class="detail-value">' . htmlspecialchars($enrollment->email ?? '') . '</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Currency</span>
                            <span class="detail-value">' . ($enrollment->plan->currency ?? 'INR') . '</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Organization Name</span>
                            <span class="detail-value">' . htmlspecialchars($enrollment->company_name ?? '') . '</span>
                        </div>
                    </div>
                </div>

                <p class="description-heading">Description</p>
                <table>
                    <thead>
                        <tr>
                            <th class="text-left">Course Name</th>
                            <th class="text-center">Qty</th>
                            <th class="text-right">Amount</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>' . $itemsHTML . '</tbody>
                </table>

                <div class="total-section">
                    <div class="total-row">
                        <div class="total-label">Total</div>
                        <div class="total-value">₹' . number_format($total, 0) . '</div>
                    </div>
                </div>

                <div class="amount-in-words">
                    <div class="amount-in-words-label">Amount in Words:</div>
                    <div class="amount-in-words-value">' . $amountInWords . '</div>
                </div>

                <div class="plan-details">
                    <div class="plan-detail-item">
                        <span class="plan-detail-label">Plan</span>
                        <span class="plan-detail-value">' . htmlspecialchars($enrollment->plan->name ?? '') . '</span>
                    </div>
                    <div class="plan-detail-item">
                        <span class="plan-detail-label">Plan Name</span>
                        <span class="plan-detail-value">' . htmlspecialchars($enrollment->plan->name ?? '') . '</span>
                    </div>
                    <div class="plan-detail-item">
                        <span class="plan-detail-label">Payment Status</span>
                        <span class="plan-detail-value">' . ucfirst($enrollment->payment_status ?? 'Not Completed') . '</span>
                    </div>
                    <div class="plan-detail-item">
                        <span class="plan-detail-label">Paid</span>
                        <span class="plan-detail-value">₹' . number_format($enrollment->paid_amount ?? 0, 0) . '</span>
                    </div>
                    <div class="plan-detail-item">
                        <span class="plan-detail-label">Transaction Date</span>
                        <span class="plan-detail-value">' . ($enrollment->payment_date ? date('d-m-Y H:i', strtotime($enrollment->payment_date)) : '-') . '</span>
                    </div>
                    <div class="plan-detail-item">
                        <span class="plan-detail-label">Date Time</span>
                        <span class="plan-detail-value">' . ($enrollment->payment_date ? date('d-m-Y H:i', strtotime($enrollment->payment_date)) : '-') . '</span>
                    </div>
                    <div class="plan-detail-item">
                        <span class="plan-detail-label">Transaction ID</span>
                        <span class="plan-detail-value">' . htmlspecialchars($enrollment->transaction_id ?? '-') . '</span>
                    </div>
                    <div class="plan-detail-item">
                        <span class="plan-detail-label">ID</span>
                        <span class="plan-detail-value">' . htmlspecialchars($enrollment->transaction_id ?? $enrollment->id) . '</span>
                    </div>
                </div>

                <div class="footer">
                    <p class="thank-you">Thank you for trusting ScholarAcad</p>
                    <p class="footer-note">This is a Computer Generated Document, No signature required</p>
                </div>
            </div>
        </body>
        </html>';
    }

    private function numberToWords($number): string
    {
        $ones = [0=>'Zero',1=>'One',2=>'Two',3=>'Three',4=>'Four',5=>'Five',6=>'Six',7=>'Seven',8=>'Eight',9=>'Nine',10=>'Ten',11=>'Eleven',12=>'Twelve',13=>'Thirteen',14=>'Fourteen',15=>'Fifteen',16=>'Sixteen',17=>'Seventeen',18=>'Eighteen',19=>'Nineteen'];
        $tens = [2=>'Twenty',3=>'Thirty',4=>'Forty',5=>'Fifty',6=>'Sixty',7=>'Seventy',8=>'Eighty',9=>'Ninety'];

        if ($number == 0) return 'Zero';
        if ($number < 20) return $ones[(int)$number];
        if ($number < 100) return $tens[floor($number/10)] . (($number%10)>0 ? ' '.$ones[$number%10] : '');

        $thousand = floor($number/1000);
        $hundred = floor(($number%1000)/100);
        $remainder = $number%100;
        $result = '';

        if ($thousand>0) $result .= $ones[$thousand].' Thousand ';
        if ($hundred>0) $result .= $ones[$hundred].' Hundred ';
        if ($remainder>0) $result .= $this->numberToWords($remainder);

        return trim($result).'.';
    }
}
