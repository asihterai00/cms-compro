<?php
require_once __DIR__ . '/../config/database.php';

// 1. Cockpit Resin Parts SVG
$cockpitSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 600" width="100%" height="100%">
  <defs>
    <linearGradient id="bgGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#0f172a" />
      <stop offset="50%" stop-color="#1e293b" />
      <stop offset="100%" stop-color="#020617" />
    </linearGradient>
    <linearGradient id="resinMatte" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#334155" />
      <stop offset="50%" stop-color="#1e293b" />
      <stop offset="100%" stop-color="#0f172a" />
    </linearGradient>
    <linearGradient id="greenAccent" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#10B981" />
      <stop offset="100%" stop-color="#005A36" />
    </linearGradient>
    <filter id="shadow3d">
      <feDropShadow dx="0" dy="12" stdDeviation="16" flood-color="#000000" flood-opacity="0.6" />
    </filter>
  </defs>

  <!-- Background Studio Backdrop -->
  <rect width="800" height="600" fill="url(#bgGrad)" />
  <circle cx="400" cy="300" r="280" fill="#005A36" opacity="0.1" filter="blur(60px)" />
  
  <!-- Technical Grid overlay -->
  <path d="M0 100 H800 M0 200 H800 M0 300 H800 M0 400 H800 M0 500 H800 M100 0 V600 M200 0 V600 M300 0 V600 M400 0 V600 M500 0 V600 M600 0 V600 M700 0 V600" stroke="#334155" stroke-width="0.5" stroke-dasharray="4,6" opacity="0.4" />

  <!-- Main Center Console / Cockpit Bezel 3D Structure -->
  <g transform="translate(140, 80)" filter="url(#shadow3d)">
    <!-- Base Panel -->
    <path d="M40 40 L480 40 Q510 40 500 90 L450 360 Q440 400 390 400 L130 400 Q80 400 70 360 L20 90 Q10 40 40 40 Z" fill="url(#resinMatte)" stroke="#475569" stroke-width="2" />
    
    <!-- Outer Bezel Groove -->
    <path d="M55 55 L465 55 Q485 55 480 95 L435 350 Q430 385 380 385 L140 385 Q90 385 85 350 L40 95 Q35 55 55 55 Z" fill="#090d16" stroke="#005A36" stroke-width="1.5" />

    <!-- Center Display Screen Opening -->
    <rect x="120" y="80" width="280" height="150" rx="10" fill="#020617" stroke="#334155" stroke-width="2" />
    <!-- Screen Display UI Graphic -->
    <rect x="125" y="85" width="270" height="140" rx="8" fill="#0B132B" />
    <path d="M140 155 Q200 120 260 155 T380 155" fill="none" stroke="#10B981" stroke-width="2" />
    <text x="260" y="125" text-anchor="middle" fill="#94A3B8" font-family="'Segoe UI', Roboto, sans-serif" font-size="12" font-weight="600">INFOTAINMENT &amp; HVAC INTERFACE</text>
    <text x="260" y="195" text-anchor="middle" fill="#10B981" font-family="'Segoe UI', Roboto, sans-serif" font-size="14" font-weight="700">PT NIHON PLAST INDONESIA</text>

    <!-- Air Conditioning Vents (Louvers) -->
    <!-- Left Vent -->
    <g transform="translate(60, 100)">
      <rect width="45" height="110" rx="6" fill="#1E293B" stroke="#475569" stroke-width="1.5" />
      <line x1="5" y1="20" x2="40" y2="20" stroke="#64748b" stroke-width="2" />
      <line x1="5" y1="40" x2="40" y2="40" stroke="#64748b" stroke-width="2" />
      <line x1="5" y1="60" x2="40" y2="60" stroke="#64748b" stroke-width="2" />
      <line x1="5" y1="80" x2="40" y2="80" stroke="#64748b" stroke-width="2" />
      <line x1="5" y1="100" x2="40" y2="100" stroke="#64748b" stroke-width="2" />
    </g>
    <!-- Right Vent -->
    <g transform="translate(415, 100)">
      <rect width="45" height="110" rx="6" fill="#1E293B" stroke="#475569" stroke-width="1.5" />
      <line x1="5" y1="20" x2="40" y2="20" stroke="#64748b" stroke-width="2" />
      <line x1="5" y1="40" x2="40" y2="40" stroke="#64748b" stroke-width="2" />
      <line x1="5" y1="60" x2="40" y2="60" stroke="#64748b" stroke-width="2" />
      <line x1="5" y1="80" x2="40" y2="80" stroke="#64748b" stroke-width="2" />
      <line x1="5" y1="100" x2="40" y2="100" stroke="#64748b" stroke-width="2" />
    </g>

    <!-- Climate Control / Push Buttons Panel -->
    <g transform="translate(130, 250)">
      <rect width="260" height="90" rx="8" fill="#1E293B" stroke="#334155" stroke-width="1.5" />
      <!-- Dials -->
      <circle cx="50" cy="45" r="24" fill="#0F172A" stroke="#64748B" stroke-width="2" />
      <circle cx="50" cy="45" r="16" fill="#1E293B" stroke="#10B981" stroke-width="1.5" />
      <circle cx="210" cy="45" r="24" fill="#0F172A" stroke="#64748B" stroke-width="2" />
      <circle cx="210" cy="45" r="16" fill="#1E293B" stroke="#10B981" stroke-width="1.5" />
      <!-- Center Push Buttons -->
      <rect x="95" y="25" width="30" height="18" rx="3" fill="#334155" />
      <rect x="135" y="25" width="30" height="18" rx="3" fill="#334155" />
      <rect x="95" y="50" width="30" height="18" rx="3" fill="#005A36" />
      <rect x="135" y="50" width="30" height="18" rx="3" fill="#334155" />
    </g>

    <!-- Lower Gear Shift Console Gateway -->
    <rect x="170" y="355" width="180" height="25" rx="5" fill="#0F172A" stroke="#334155" />
  </g>

  <!-- Technical Spec Callout Badge -->
  <g transform="translate(30, 480)">
    <rect width="320" height="85" rx="8" fill="#0F172A" stroke="#005A36" stroke-width="1.5" />
    <text x="20" y="28" fill="#10B981" font-family="'Segoe UI', Roboto, sans-serif" font-weight="800" font-size="13" letter-spacing="1">TECHNICAL SPECIFICATIONS</text>
    <text x="20" y="48" fill="#FFFFFF" font-family="'Segoe UI', Roboto, sans-serif" font-size="12">Material: Engineering Polypropylene (PP+EPDM / ABS)</text>
    <text x="20" y="68" fill="#94A3B8" font-family="'Segoe UI', Roboto, sans-serif" font-size="11">Process: High-Tonnage Precision Injection + Fine Grain Grain Etch</text>
  </g>

  <g transform="translate(450, 480)">
    <rect width="320" height="85" rx="8" fill="#0F172A" stroke="#005A36" stroke-width="1.5" />
    <text x="20" y="28" fill="#10B981" font-family="'Segoe UI', Roboto, sans-serif" font-weight="800" font-size="13" letter-spacing="1">QUALITY COMPLIANCE</text>
    <text x="20" y="48" fill="#FFFFFF" font-family="'Segoe UI', Roboto, sans-serif" font-size="12">Standard: IATF 16949 / JIS D0202 Weatherability</text>
    <text x="20" y="68" fill="#94A3B8" font-family="'Segoe UI', Roboto, sans-serif" font-size="11">Fitment: Zero Gap Tolerance ±0.2mm Snap-Fit</text>
  </g>
</svg>
SVG;

// 2. Exterior & Aerodynamic Trim SVG
$exteriorSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 600" width="100%" height="100%">
  <defs>
    <linearGradient id="extBg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#020617" />
      <stop offset="50%" stop-color="#0F172A" />
      <stop offset="100%" stop-color="#1E293B" />
    </linearGradient>
    <linearGradient id="glossBlack" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#334155" />
      <stop offset="20%" stop-color="#0F172A" />
      <stop offset="80%" stop-color="#020617" />
      <stop offset="100%" stop-color="#1E293B" />
    </linearGradient>
    <linearGradient id="chromeGleam" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#FFFFFF" />
      <stop offset="50%" stop-color="#94A3B8" />
      <stop offset="100%" stop-color="#475569" />
    </linearGradient>
    <filter id="extShadow">
      <feDropShadow dx="0" dy="14" stdDeviation="18" flood-color="#000000" flood-opacity="0.65" />
    </filter>
  </defs>

  <rect width="800" height="600" fill="url(#extBg)" />
  <circle cx="400" cy="280" r="260" fill="#005A36" opacity="0.12" filter="blur(70px)" />

  <!-- Technical Grid lines -->
  <path d="M0 150 H800 M0 300 H800 M0 450 H800 M200 0 V600 M400 0 V600 M600 0 V600" stroke="#334155" stroke-width="0.5" stroke-dasharray="3,6" opacity="0.4" />

  <!-- Front Radiator Grille & Aerodynamic Assembly -->
  <g transform="translate(100, 110)" filter="url(#extShadow)">
    <!-- Main Grille Outer Shield Frame -->
    <path d="M50 30 L550 30 Q580 30 560 90 L510 240 Q490 290 400 300 L200 300 Q110 290 90 240 L40 90 Q20 30 50 30 Z" fill="url(#glossBlack)" stroke="url(#chromeGleam)" stroke-width="3" />
    
    <!-- Honeycomb Aerodynamic Mesh Structure -->
    <g fill="#020617" stroke="#10B981" stroke-width="1" opacity="0.85">
      <!-- Row 1 -->
      <polygon points="120,60 140,50 160,60 160,80 140,90 120,80" />
      <polygon points="170,60 190,50 210,60 210,80 190,90 170,80" />
      <polygon points="220,60 240,50 260,60 260,80 240,90 220,80" />
      <polygon points="270,60 290,50 310,60 310,80 290,90 270,80" />
      <polygon points="320,60 340,50 360,60 360,80 340,90 320,80" />
      <polygon points="370,60 390,50 410,60 410,80 390,90 370,80" />
      <polygon points="420,60 440,50 460,60 460,80 440,90 420,80" />
      <polygon points="470,60 490,50 510,60 510,80 490,90 470,80" />
      <!-- Row 2 -->
      <polygon points="145,95 165,85 185,95 185,115 165,125 145,115" />
      <polygon points="195,95 215,85 235,95 235,115 215,125 195,115" />
      <polygon points="245,95 265,85 285,95 285,115 265,125 245,115" />
      <polygon points="295,95 315,85 335,95 335,115 315,125 295,115" />
      <polygon points="345,95 365,85 385,95 385,115 365,125 345,115" />
      <polygon points="395,95 415,85 435,95 435,115 415,125 395,115" />
      <polygon points="445,95 465,85 485,95 485,115 465,125 445,115" />
      <!-- Row 3 -->
      <polygon points="160,130 180,120 200,130 200,150 180,160 160,150" />
      <polygon points="210,130 230,120 250,130 250,150 230,160 210,150" />
      <polygon points="260,130 280,120 300,130 300,150 280,160 260,150" />
      <polygon points="310,130 330,120 350,130 350,150 330,160 310,150" />
      <polygon points="360,130 380,120 400,130 400,150 380,160 360,150" />
      <polygon points="410,130 430,120 450,130 450,150 430,160 410,150" />
      <!-- Row 4 -->
      <polygon points="185,165 205,155 225,165 225,185 205,195 185,185" />
      <polygon points="235,165 255,155 275,165 275,185 255,195 235,185" />
      <polygon points="285,165 305,155 325,165 325,185 305,195 285,185" />
      <polygon points="335,165 355,155 375,165 375,185 355,195 335,185" />
      <polygon points="385,165 405,155 425,165 425,185 405,195 385,185" />
    </g>

    <!-- Center OEM Mount Platform -->
    <rect x="230" y="90" width="140" height="90" rx="10" fill="#0A3C28" stroke="#10B981" stroke-width="2" />
    <circle cx="300" cy="135" r="28" fill="#020617" stroke="#FCD34D" stroke-width="2" />
    <path d="M285 135 L300 120 L315 135 L300 150 Z" fill="#FCD34D" />

    <!-- Side Door Mirror Housing (Floating Preview) -->
    <g transform="translate(480, -30)">
      <path d="M10 50 Q40 10 90 20 Q120 28 130 60 Q120 90 70 85 Q20 80 10 50 Z" fill="url(#glossBlack)" stroke="#64748B" stroke-width="2" />
      <!-- Turn Signal LED Indicator Bar -->
      <path d="M30 55 Q70 45 105 52" fill="none" stroke="#F59E0B" stroke-width="3.5" stroke-linecap="round" />
    </g>
  </g>

  <!-- Technical Specs Cards -->
  <g transform="translate(30, 480)">
    <rect width="350" height="85" rx="8" fill="#0F172A" stroke="#005A36" stroke-width="1.5" />
    <text x="20" y="28" fill="#10B981" font-family="'Segoe UI', Roboto, sans-serif" font-weight="800" font-size="13" letter-spacing="1">AERODYNAMIC &amp; THERMAL SPECS</text>
    <text x="20" y="48" fill="#FFFFFF" font-family="'Segoe UI', Roboto, sans-serif" font-size="12">Airflow Resistance: Optimised CFD Drag Coefficient</text>
    <text x="20" y="68" fill="#94A3B8" font-family="'Segoe UI', Roboto, sans-serif" font-size="11">Heat Resistance: 120°C Engine Bay Underhood Exposure</text>
  </g>

  <g transform="translate(420, 480)">
    <rect width="350" height="85" rx="8" fill="#0F172A" stroke="#005A36" stroke-width="1.5" />
    <text x="20" y="28" fill="#10B981" font-family="'Segoe UI', Roboto, sans-serif" font-weight="800" font-size="13" letter-spacing="1">SURFACE FINISH CAPABILITIES</text>
    <text x="20" y="48" fill="#FFFFFF" font-family="'Segoe UI', Roboto, sans-serif" font-size="12">Coating: High-Gloss Piano Black / Chrome Vacuum Plating</text>
    <text x="20" y="68" fill="#94A3B8" font-family="'Segoe UI', Roboto, sans-serif" font-size="11">UV Resistance: 2,000 Hours Xenon Arc Weathering (SAE J2527)</text>
  </g>
</svg>
SVG;

// 3. Cleanroom & Testing Facility SVG
$facilityLabSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 600" width="100%" height="100%">
  <defs>
    <linearGradient id="cleanBg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#022c22" />
      <stop offset="50%" stop-color="#064e3b" />
      <stop offset="100%" stop-color="#020617" />
    </linearGradient>
    <filter id="cleanGlow">
      <feDropShadow dx="0" dy="8" stdDeviation="14" flood-color="#10B981" flood-opacity="0.3" />
    </filter>
  </defs>

  <rect width="800" height="600" fill="url(#cleanBg)" />
  
  <!-- Cleanroom Ceiling HEPA Filter Grids -->
  <g stroke="#34D399" stroke-width="1" opacity="0.3">
    <rect x="50" y="20" width="150" height="70" fill="#064e3b" />
    <rect x="230" y="20" width="150" height="70" fill="#064e3b" />
    <rect x="410" y="20" width="150" height="70" fill="#064e3b" />
    <rect x="590" y="20" width="150" height="70" fill="#064e3b" />
  </g>

  <!-- 3D Coordinate Measuring Machine (CMM) & Airbag Deployment Rig -->
  <g transform="translate(180, 120)" filter="url(#cleanGlow)">
    <!-- Granite Surface Base Table -->
    <rect x="20" y="260" width="400" height="40" rx="4" fill="#0F172A" stroke="#334155" stroke-width="2" />
    <!-- Table Legs -->
    <rect x="40" y="300" width="30" height="60" fill="#1E293B" />
    <rect x="370" y="300" width="30" height="60" fill="#1E293B" />

    <!-- CMM Bridge Gantry -->
    <rect x="60" y="60" width="30" height="200" fill="#E2E8F0" stroke="#94A3B8" stroke-width="2" />
    <rect x="350" y="60" width="30" height="200" fill="#E2E8F0" stroke="#94A3B8" stroke-width="2" />
    <rect x="60" y="40" width="320" height="30" rx="3" fill="#005A36" stroke="#10B981" stroke-width="2" />
    
    <!-- Measuring Head Probe Assembly -->
    <rect x="200" y="65" width="40" height="50" fill="#0F172A" stroke="#34D399" />
    <line x1="220" y1="115" x2="220" y2="190" stroke="#EF4444" stroke-width="3" />
    <circle cx="220" cy="192" r="5" fill="#EF4444" />
    <!-- Laser Measurement Ring Beam -->
    <circle cx="220" cy="220" r="28" fill="none" stroke="#10B981" stroke-width="1.5" stroke-dasharray="4,4" />

    <!-- Steering Wheel Under Inspection on Base Table -->
    <ellipse cx="220" cy="245" rx="55" ry="25" fill="#1E293B" stroke="#64748B" stroke-width="4" />
    <ellipse cx="220" cy="245" rx="20" ry="10" fill="#005A36" />
  </g>

  <!-- Inspection Terminal Screen Display -->
  <g transform="translate(560, 200)">
    <rect width="180" height="130" rx="6" fill="#0F172A" stroke="#10B981" stroke-width="1.5" />
    <!-- Screen Header -->
    <rect width="180" height="25" rx="6" fill="#064E3B" />
    <text x="90" y="17" text-anchor="middle" fill="#FFFFFF" font-family="'Segoe UI', Roboto, sans-serif" font-size="10" font-weight="700">CMM PRECISION INSPECTION</text>
    <!-- Waveform / Tolerance Scan -->
    <path d="M15 65 L45 55 L75 75 L105 45 L135 65 L165 50" fill="none" stroke="#34D399" stroke-width="2" />
    <text x="15" y="95" fill="#94A3B8" font-family="'Segoe UI', Roboto, sans-serif" font-size="9">Tolerance: ±0.005 mm</text>
    <text x="15" y="112" fill="#10B981" font-family="'Segoe UI', Roboto, sans-serif" font-size="11" font-weight="800">STATUS: PASS (100% OK)</text>
  </g>

  <!-- Cleanroom Specs Footer -->
  <g transform="translate(40, 500)">
    <rect width="720" height="70" rx="8" fill="#020617" stroke="#10B981" stroke-width="1.5" />
    <text x="25" y="26" fill="#10B981" font-family="'Segoe UI', Roboto, sans-serif" font-weight="800" font-size="12" letter-spacing="1">CLEANROOM CLASS 100,000 &amp; LABORATORY SPECIFICATIONS</text>
    <text x="25" y="46" fill="#FFFFFF" font-family="'Segoe UI', Roboto, sans-serif" font-size="11">Instrumentation: Mitutoyo 3D CMM, Airbag Deployment Chamber, Xenon Arc UV Weatherometer, Gas Leak Sniffer</text>
    <text x="25" y="60" fill="#94A3B8" font-family="'Segoe UI', Roboto, sans-serif" font-size="10">Standards: IATF 16949 Metrology Traceability, JIS D1601 Vibration Test, ISO 17025 Ready</text>
  </g>
</svg>
SVG;

// Save files
file_put_contents(__DIR__ . '/../uploads/products/product_cockpit_resin.svg', $cockpitSvg);
file_put_contents(__DIR__ . '/../uploads/products/product_exterior_trim.svg', $exteriorSvg);
file_put_contents(__DIR__ . '/../uploads/gallery/facility_cleanroom_lab.svg', $facilityLabSvg);

echo "Created product and facility SVG visual assets.\n";

// Update database products table
$conn->query("UPDATE products SET image = 'uploads/products/product_steering_wheel.jpg' WHERE id = 1");
$conn->query("UPDATE products SET image = 'uploads/products/product_airbag_module.jpg' WHERE id = 2");
$conn->query("UPDATE products SET image = 'uploads/products/product_cockpit_resin.svg' WHERE id = 3");
$conn->query("UPDATE products SET image = 'uploads/products/product_exterior_trim.svg' WHERE id = 4");
echo "Updated products table with local high-res and vector images.\n";

// Update database gallery table
$conn->query("UPDATE gallery SET image = 'uploads/gallery/facility_injection_molding.jpg' WHERE id = 1");
$conn->query("UPDATE gallery SET image = 'uploads/gallery/facility_cleanroom_lab.svg' WHERE id = 2");
echo "Updated gallery table with local high-res and vector images.\n";

// Update site_sections external unsplash links to local assets
$conn->query("UPDATE site_sections SET image = 'uploads/gallery/facility_injection_molding.jpg' WHERE id = 14");
$conn->query("UPDATE site_sections SET image = 'uploads/gallery/facility_cleanroom_lab.svg' WHERE id = 19");
$conn->query("UPDATE site_sections SET image = 'uploads/products/product_steering_wheel.jpg' WHERE id = 22");
echo "Updated site_sections unsplash images to local high-res assets.\n";

// Update page_banners so every page has a default local banner
$bannerAssignments = [
    'home' => 'uploads/pages/banner_home.jpg',
    'about' => 'uploads/gallery/facility_injection_molding.jpg',
    'facility' => 'uploads/gallery/facility_injection_molding.jpg',
    'quality' => 'uploads/gallery/facility_cleanroom_lab.svg',
    'products' => 'uploads/products/product_steering_wheel.jpg',
    'contact' => 'uploads/gallery/facility_injection_molding.jpg',
    'career' => 'uploads/gallery/facility_injection_molding.jpg',
    'gallery' => 'uploads/gallery/facility_injection_molding.jpg',
    'news' => 'uploads/gallery/facility_cleanroom_lab.svg'
];

foreach ($bannerAssignments as $pKey => $img) {
    $stmt = $conn->prepare("UPDATE page_banners SET image = ? WHERE page_key = ?");
    $stmt->bind_param("ss", $img, $pKey);
    $stmt->execute();
    $stmt->close();
}
echo "Updated page_banners table with local banner assets.\n";
