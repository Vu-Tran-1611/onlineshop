<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeBaseDocument;
use Illuminate\Support\Facades\Response;

class PdfController extends Controller
{
    public function downloadPoliciesPdf()
    {
        // Fetch all knowledge base documents ordered by creation
        $documents = KnowledgeBaseDocument::all()->sortBy('document_type');

        // Generate HTML content
        $html = $this->generatePdfHtml($documents);

        // Create filename with timestamp
        $filename = 'policies_' . date('Y-m-d_H-i-s') . '.html';

        // Return as downloadable file
        return Response::make($html, 200, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function generatePdfHtml($documents)
    {
        $html = <<<'HTML'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Policies & Documentation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: white;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .cover-page {
            text-align: center;
            page-break-after: always;
            padding: 100px 0;
            border-bottom: 2px solid #333;
            margin-bottom: 40px;
        }

        .cover-page h1 {
            font-size: 48px;
            margin-bottom: 20px;
            color: #0b1020;
        }

        .cover-page p {
            font-size: 18px;
            color: #666;
            margin-bottom: 10px;
        }

        .cover-page .date {
            font-size: 14px;
            color: #999;
            margin-top: 40px;
        }

        .table-of-contents {
            page-break-after: always;
            margin-bottom: 40px;
        }

        .table-of-contents h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #0b1020;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }

        .table-of-contents ul {
            list-style: none;
        }

        .table-of-contents li {
            margin: 10px 0;
            padding-left: 20px;
        }

        .table-of-contents a {
            color: #0066cc;
            text-decoration: none;
        }

        .document {
            page-break-after: always;
            margin-bottom: 50px;
            padding: 30px;
            background-color: #fafafa;
            border-radius: 8px;
        }

        .document:last-child {
            page-break-after: auto;
        }

        .document h1 {
            font-size: 32px;
            color: #0b1020;
            margin-bottom: 15px;
            border-bottom: 3px solid #0066cc;
            padding-bottom: 10px;
        }

        .document h2 {
            font-size: 20px;
            color: #333;
            margin-top: 25px;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .document p {
            margin-bottom: 12px;
            text-align: justify;
            color: #555;
        }

        .document ul,
        .document ol {
            margin: 15px 0 15px 30px;
            color: #555;
        }

        .document li {
            margin-bottom: 8px;
            line-height: 1.8;
        }

        .document ul li {
            list-style-type: disc;
        }

        .document ol li {
            list-style-type: decimal;
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
        }

        .header img {
            max-width: 150px;
            margin-bottom: 10px;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #999;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        .page-number {
            text-align: right;
            color: #999;
            font-size: 12px;
            margin-top: 20px;
        }

        @media print {
            body {
                background-color: white;
            }
            .container {
                box-shadow: none;
                max-width: 100%;
                padding: 0;
            }
            .document {
                page-break-after: always;
                background-color: white;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Cover Page -->
        <div class="cover-page">
            <h1>Store Policies & Documentation</h1>
            <p>Complete Guide to Our Services</p>
            <p class="date">Generated on:
HTML;

        $html .= date('F j, Y');
        $html .= '</p>';
        $html .= '</div>';

        // Table of Contents
        $html .= '<div class="table-of-contents">';
        $html .= '<h2>Table of Contents</h2>';
        $html .= '<ul>';

        foreach ($documents as $index => $doc) {
            $html .= '<li>' . ($index + 1) . '. ' . htmlspecialchars($doc->title) . '</li>';
        }

        $html .= '</ul></div>';

        // Documents
        foreach ($documents as $index => $doc) {
            $html .= '<div class="document">';
            $html .= $doc->content;
            $html .= '<div class="page-number">Page ' . ($index + 2) . '</div>';
            $html .= '</div>';
        }

        // Footer
        $html .= '<div class="footer">';
        $html .= '<p>&copy; ' . date('Y') . ' All Rights Reserved. This document contains proprietary information.</p>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</body>';
        $html .= '</html>';

        return $html;
    }
}
