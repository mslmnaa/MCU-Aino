<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Checkup Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            background: #fff;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        /* Main sections */
        .section {
            margin-bottom: 25px;
        }

        .main-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .sub-section {
            margin-left: 20px;
            margin-bottom: 8px;
        }

        .sub-title {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
            margin-top: 10px;
        }

        /* List items */
        .result-list {
            margin-left: 20px;
        }

        .result-item {
            display: flex;
            margin-bottom: 5px;
            line-height: 1.3;
        }

        .item-number {
            min-width: 25px;
        }

        .item-label {
            min-width: 200px;
            flex: 0 0 200px;
        }

        .item-colon {
            margin: 0 10px;
        }

        .item-value {
            flex: 1;
        }

        /* Table styles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 11px;
        }

        .data-table td, .data-table th {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        .data-table th {
            font-weight: bold;
            text-align: center;
            background: #f5f5f5;
        }

        .data-table td:first-child {
            font-weight: bold;
        }

        .data-table .section-header {
            font-weight: bold;
            background: #f9f9f9;
        }

        .data-table .sub-item {
            padding-left: 20px;
        }

        .data-table td:nth-child(2) {
            text-align: center;
        }

        .data-table td:nth-child(3) {
            text-align: center;
        }

        .data-table td:nth-child(4) {
            text-align: center;
        }

        /* Recommendation section */
        .recommendation-section {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .recommendation-text {
            margin-left: 20px;
            line-height: 1.5;
        }

        /* Page break for printing */
        @media print {
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>
    <!-- RANGKUMAN HASIL -->
    <div class="section">
        <div class="main-title">A. RANGKUMAN HASIL</div>
        
        <!-- Laboratorium Section -->
        <div class="sub-section">
            <div class="sub-title">I. LABORATORIUM</div>
            <div class="result-list">
                <div class="result-item">
                    <span class="item-number">1.</span>
                    <span class="item-label">Hematologi Lengkap</span>
                    <span class="item-colon">:</span>
                    <span class="item-value">Eosinofil (Hitung Jenis) sedikit meningkat ( 9 )</span>
                </div>
                <div class="result-item">
                    <span class="item-number">2.</span>
                    <span class="item-label">Urine Lengkap</span>
                    <span class="item-colon">:</span>
                    <span class="item-value">Urine Blood (Ery/Hb) Positif 2 (25 /μL) ( 2 - 4 /lp )</span>
                </div>
                <div class="result-item">
                    <span class="item-number">3.</span>
                    <span class="item-label">Fungsi Liver</span>
                    <span class="item-colon">:</span>
                    <span class="item-value">Dalam batas normal</span>
                </div>
                <div class="result-item">
                    <span class="item-number">4.</span>
                    <span class="item-label">Profil Lemak</span>
                    <span class="item-colon">:</span>
                    <span class="item-value">Dalam batas normal</span>
                </div>
                <div class="result-item">
                    <span class="item-number">5.</span>
                    <span class="item-label">Fungsi Ginjal</span>
                    <span class="item-colon">:</span>
                    <span class="item-value">Dalam batas normal</span>
                </div>
                <div class="result-item">
                    <span class="item-number">6.</span>
                    <span class="item-label">Glukosa Darah Puasa</span>
                    <span class="item-colon">:</span>
                    <span class="item-value">Dalam batas normal</span>
                </div>
                <div class="result-item">
                    <span class="item-number">7.</span>
                    <span class="item-label">Glukosa Darah 2 Jam PP</span>
                    <span class="item-colon">:</span>
                    <span class="item-value">Dalam batas normal</span>
                </div>
                <div class="result-item">
                    <span class="item-number">8.</span>
                    <span class="item-label">HbA1c</span>
                    <span class="item-colon">:</span>
                    <span class="item-value">Dalam batas normal</span>
                </div>
            </div>
        </div>

        <!-- Non Laboratorium Section -->
        <div class="sub-section">
            <div class="sub-title">II. NON LABORATORIUM</div>
            <div class="result-list">
                <div class="result-item">
                    <span class="item-number">1.</span>
                    <span class="item-label">ECG</span>
                    <span class="item-colon">:</span>
                    <span class="item-value">Normal ECG</span>
                </div>
            </div>
        </div>

        <!-- Pemeriksaan Dokter Section -->
        <div class="sub-section">
            <div class="sub-title">III. PEMERIKSAAN DOKTER</div>
            <div class="result-list">
                <div class="result-item">
                    <span class="item-number">1.</span>
                    <span class="item-label">Pemeriksaan Fisik Umum</span>
                    <span class="item-colon">:</span>
                    <span class="item-value">Pada saat ini didapatkan kelainan berupa :<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kelainan Refraksi Terkoreksi Dengan kacamata</span>
                </div>
            </div>
        </div>
    </div>

    <!-- SARAN -->
    <div class="section">
        <div class="main-title">B. SARAN :</div>
        <div class="recommendation-text">
            Cek mata berkala, cukup minum air putih, jaga daya tahan tubuh.
        </div>
    </div>

    <!-- Page break for second page -->
    <div class="page-break"></div>

    <!-- Detail Results Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 35%">JENIS PEMERIKSAAN</th>
                <th style="width: 20%">HASIL</th>
                <th style="width: 25%">NILAI RUJUKAN</th>
                <th style="width: 20%">SATUAN</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4" class="section-header">HEMATOLOGI</td>
            </tr>
            <tr>
                <td style="font-style: italic; padding-left: 10px;">HEMATOLOGI RUTIN</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="sub-item">Hematologi Lengkap</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="sub-item" style="padding-left: 30px;">Hemoglobin</td>
                <td>15,4</td>
                <td>13,0 - 18,0</td>
                <td>g/dL</td>
            </tr>
            <tr>
                <td class="sub-item" style="padding-left: 30px;">Erytrosit</td>
                <td>5,39</td>
                <td>4,20 - 6,00</td>
                <td>10⁶/μL</td>
            </tr>
            <tr>
                <td class="sub-item" style="padding-left: 30px;">Hematokrit</td>
                <td>47</td>
                <td>40 - 54</td>
                <td>%</td>
            </tr>
            <tr>
                <td class="sub-item" style="padding-left: 30px;">MCV</td>
                <td>87</td>
                <td>80 - 100</td>
                <td>fL</td>
            </tr>
            <tr>
                <td class="sub-item" style="padding-left: 30px;">MCH</td>
                <td>29</td>
                <td>26 - 34</td>
                <td>pg/cell</td>
            </tr>
            <tr>
                <td class="sub-item" style="padding-left: 30px;">MCHC</td>
                <td>33</td>
                <td>32 - 36</td>
                <td>g/dL</td>
            </tr>
            <tr>
                <td class="sub-item" style="padding-left: 30px;">RDW</td>
                <td>12,8</td>
                <td>11,5 - 14,5</td>
                <td>%</td>
            </tr>
            <tr>
                <td class="sub-item" style="padding-left: 30px;">Leukosit</td>
                <td>5.800</td>
                <td>3.600 - 10.600</td>
                <td>/μL</td>
            </tr>
            <tr>
                <td class="sub-item">Hitung Jenis</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="sub-item" style="padding-left: 30px;">• Eosinofil</td>
                <td style="font-weight: bold;">9</td>
                <td>0 - 3</td>
                <td>%</td>
            </tr>
            <tr>
                <td class="sub-item" style="padding-left: 30px;">• Basofil</td>
                <td>1</td>
                <td>0 - 2</td>
                <td>%</td>
            </tr>
            <tr>
                <td class="sub-item" style="padding-left: 30px;">• Neutrofil Batang</td>
                <td>0</td>
                <td>3 - 5</td>
                <td>%</td>
            </tr>
            <tr>
                <td class="sub-item" style="padding-left: 30px;">• Neutrofil Segmen</td>
                <td>56</td>
                <td>50 - 70</td>
                <td>%</td>
            </tr>
            <tr>
                <td class="sub-item" style="padding-left: 30px;">• Limfosit</td>
                <td>27</td>
                <td>18 - 42</td>
                <td>%</td>
            </tr>
            <tr>
                <td class="sub-item" style="padding-left: 30px;">• Monosit</td>
                <td>7</td>
                <td>2 - 11</td>
                <td>%</td>
            </tr>
            <tr>
                <td class="sub-item">Trombosit</td>
                <td>242.000</td>
                <td>150.000 - 450.000</td>
                <td>/μL</td>
            </tr>
            <tr>
                <td class="sub-item">Laju Endap Darah (LED)</td>
                <td>6</td>
                <td>0 - 15</td>
                <td>mm/jam</td>
            </tr>
            <tr>
                <td colspan="4" class="section-header">URINALISIS</td>
            </tr>
            <tr>
                <td style="font-style: italic; padding-left: 10px;">URINALISIS RUTIN</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="sub-item">Urine Lengkap</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="sub-item" style="padding-left: 30px;">Warna</td>
                <td>Kuning muda</td>
                <td>-</td>
                <td></td>
            </tr>
            <tr>
                <td class="sub-item" style="padding-left: 30px;">Kejernihan</td>
                <td>Jernih</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="sub-item" style="padding-left: 30px;">BJ Urine</td>
                <td>1,010</td>
                <td>1,015 - 1,025</td>
                <td></td>
            </tr>
            <tr>
                <td class="sub-item" style="padding-left: 30px;">pH Urine</td>
                <td>6,0</td>
                <td>4,8 - 7,4</td>
                <td></td>
            </tr>
            <tr>
                <td class="sub-item" style="padding-left: 30px;">Protein (Albumin) Urine</td>
                <td>Negatif</td>
                <td>Negatif</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <p style="text-align: center; margin-top: 20px; font-size: 11px;">1 / 3</p>
</body>
</html>