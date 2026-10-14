<?php
require_once __DIR__ . '/../config/database.php';

$dir = __DIR__ . '/../uploads/certifications/';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

// 1. IATF 16949 Badge SVG
$iatfSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 320" width="100%" height="100%">
  <defs>
    <linearGradient id="iatfGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#005A36" />
      <stop offset="50%" stop-color="#0A3C28" />
      <stop offset="100%" stop-color="#022115" />
    </linearGradient>
    <linearGradient id="goldRing" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#FCD34D" />
      <stop offset="50%" stop-color="#D97706" />
      <stop offset="100%" stop-color="#92400E" />
    </linearGradient>
    <filter id="badgeShadow" x="-10%" y="-10%" width="120%" height="120%">
      <feDropShadow dx="0" dy="6" stdDeviation="8" flood-color="#022115" flood-opacity="0.35" />
    </filter>
  </defs>
  <circle cx="160" cy="160" r="148" fill="url(#goldRing)" filter="url(#badgeShadow)" />
  <circle cx="160" cy="160" r="138" fill="#FFFFFF" />
  <circle cx="160" cy="160" r="130" fill="url(#iatfGrad)" />
  <circle cx="160" cy="160" r="124" fill="none" stroke="#FCD34D" stroke-width="2" stroke-dasharray="6,4" />
  
  <!-- Ribbon / Icon -->
  <g transform="translate(160, 85)" text-anchor="middle">
    <!-- Star cluster -->
    <path d="M0 -18 L4 -6 L17 -6 L7 2 L11 14 L0 7 L-11 14 L-7 2 L-17 -6 L-4 -6 Z" fill="#FCD34D" />
    <path d="M-40 -10 L-37 -2 L-28 -2 L-35 4 L-32 12 L-40 7 L-48 12 L-45 4 L-52 -2 L-43 -2 Z" fill="#FCD34D" transform="scale(0.65)" />
    <path d="M40 -10 L43 -2 L52 -2 L45 4 L48 12 L40 7 L32 12 L35 4 L28 -2 L37 -2 Z" fill="#FCD34D" transform="scale(0.65)" />
  </g>
  
  <!-- Text -->
  <text x="160" y="145" text-anchor="middle" fill="#FFFFFF" font-family="'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-weight="900" font-size="34" letter-spacing="1">IATF</text>
  <text x="160" y="185" text-anchor="middle" fill="#FCD34D" font-family="'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-weight="800" font-size="30" letter-spacing="2">16949:2016</text>
  
  <!-- Badge Subtext -->
  <rect x="55" y="205" width="210" height="26" rx="13" fill="#0A3C28" stroke="#FCD34D" stroke-width="1.5" />
  <text x="160" y="222" text-anchor="middle" fill="#FFFFFF" font-family="'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-weight="700" font-size="11" letter-spacing="1.5">AUTOMOTIVE QUALITY</text>
  
  <text x="160" y="252" text-anchor="middle" fill="#9AE6B4" font-family="'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-weight="600" font-size="10" letter-spacing="1">TIER-1 OEM CERTIFIED</text>
  <text x="160" y="268" text-anchor="middle" fill="#E2E8F0" font-family="'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-weight="500" font-size="9">PT NIHON PLAST INDONESIA</text>
</svg>
SVG;

// 2. ISO 9001 Badge SVG
$iso9001Svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 320" width="100%" height="100%">
  <defs>
    <linearGradient id="iso9001Grad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#1E3A8A" />
      <stop offset="50%" stop-color="#0F172A" />
      <stop offset="100%" stop-color="#020617" />
    </linearGradient>
    <linearGradient id="silverRing" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#F1F5F9" />
      <stop offset="50%" stop-color="#94A3B8" />
      <stop offset="100%" stop-color="#475569" />
    </linearGradient>
    <filter id="isoShadow" x="-10%" y="-10%" width="120%" height="120%">
      <feDropShadow dx="0" dy="6" stdDeviation="8" flood-color="#0F172A" flood-opacity="0.35" />
    </filter>
  </defs>
  <circle cx="160" cy="160" r="148" fill="url(#silverRing)" filter="url(#isoShadow)" />
  <circle cx="160" cy="160" r="138" fill="#FFFFFF" />
  <circle cx="160" cy="160" r="130" fill="url(#iso9001Grad)" />
  <circle cx="160" cy="160" r="124" fill="none" stroke="#60A5FA" stroke-width="2" stroke-dasharray="6,4" />
  
  <g transform="translate(160, 85)" text-anchor="middle">
    <!-- Checkmark Shield Icon -->
    <path d="M0 -15 L20 -5 L20 12 Q20 25 0 35 Q-20 25 -20 12 L-20 -5 Z" fill="#2563EB" stroke="#93C5FD" stroke-width="2" />
    <path d="M-8 8 L-2 14 L10 2" fill="none" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
  </g>
  
  <text x="160" y="152" text-anchor="middle" fill="#FFFFFF" font-family="'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-weight="900" font-size="34" letter-spacing="1">ISO</text>
  <text x="160" y="190" text-anchor="middle" fill="#93C5FD" font-family="'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-weight="800" font-size="30" letter-spacing="2">9001:2015</text>
  
  <rect x="55" y="208" width="210" height="26" rx="13" fill="#1E293B" stroke="#60A5FA" stroke-width="1.5" />
  <text x="160" y="225" text-anchor="middle" fill="#FFFFFF" font-family="'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-weight="700" font-size="11" letter-spacing="1.5">QUALITY MANAGEMENT</text>
  
  <text x="160" y="254" text-anchor="middle" fill="#93C5FD" font-family="'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-weight="600" font-size="10" letter-spacing="1">GLOBAL STANDARD VERIFIED</text>
  <text x="160" y="270" text-anchor="middle" fill="#E2E8F0" font-family="'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-weight="500" font-size="9">PT NIHON PLAST INDONESIA</text>
</svg>
SVG;

// 3. ISO 14001 Badge SVG (Environmental)
$iso14001Svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 320" width="100%" height="100%">
  <defs>
    <linearGradient id="iso14001Grad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#047857" />
      <stop offset="50%" stop-color="#064E3B" />
      <stop offset="100%" stop-color="#022C22" />
    </linearGradient>
    <linearGradient id="greenRing" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#6EE7B7" />
      <stop offset="50%" stop-color="#059669" />
      <stop offset="100%" stop-color="#047857" />
    </linearGradient>
    <filter id="envShadow" x="-10%" y="-10%" width="120%" height="120%">
      <feDropShadow dx="0" dy="6" stdDeviation="8" flood-color="#064E3B" flood-opacity="0.35" />
    </filter>
  </defs>
  <circle cx="160" cy="160" r="148" fill="url(#greenRing)" filter="url(#envShadow)" />
  <circle cx="160" cy="160" r="138" fill="#FFFFFF" />
  <circle cx="160" cy="160" r="130" fill="url(#iso14001Grad)" />
  <circle cx="160" cy="160" r="124" fill="none" stroke="#6EE7B7" stroke-width="2" stroke-dasharray="6,4" />
  
  <g transform="translate(160, 85)" text-anchor="middle">
    <!-- Leaf / Eco Icon -->
    <path d="M0 -15 C15 -15 25 -5 25 15 C25 25 15 30 0 32 C-15 30 -25 25 -25 15 C-25 -5 -15 -15 0 -15 Z" fill="#10B981" />
    <path d="M0 -10 Q0 15 0 28 M0 5 Q10 0 16 -5 M0 15 Q-10 10 -16 5" fill="none" stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round" />
  </g>
  
  <text x="160" y="152" text-anchor="middle" fill="#FFFFFF" font-family="'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-weight="900" font-size="34" letter-spacing="1">ISO</text>
  <text x="160" y="190" text-anchor="middle" fill="#6EE7B7" font-family="'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-weight="800" font-size="30" letter-spacing="2">14001:2015</text>
  
  <rect x="55" y="208" width="210" height="26" rx="13" fill="#064E3B" stroke="#34D399" stroke-width="1.5" />
  <text x="160" y="225" text-anchor="middle" fill="#FFFFFF" font-family="'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-weight="700" font-size="11" letter-spacing="1.5">ENVIRONMENTAL SYSTEM</text>
  
  <text x="160" y="254" text-anchor="middle" fill="#A7F3D0" font-family="'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-weight="600" font-size="10" letter-spacing="1">GREEN FACTORY &amp; ESG</text>
  <text x="160" y="270" text-anchor="middle" fill="#E2E8F0" font-family="'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-weight="500" font-size="9">PT NIHON PLAST INDONESIA</text>
</svg>
SVG;

// 4. ISO 45001 Badge SVG (Occupational Health & Safety)
$iso45001Svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 320" width="100%" height="100%">
  <defs>
    <linearGradient id="iso45001Grad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#C2410C" />
      <stop offset="50%" stop-color="#7C2D12" />
      <stop offset="100%" stop-color="#431407" />
    </linearGradient>
    <linearGradient id="amberRing" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#FBBF24" />
      <stop offset="50%" stop-color="#D97706" />
      <stop offset="100%" stop-color="#92400E" />
    </linearGradient>
    <filter id="safetyShadow" x="-10%" y="-10%" width="120%" height="120%">
      <feDropShadow dx="0" dy="6" stdDeviation="8" flood-color="#431407" flood-opacity="0.35" />
    </filter>
  </defs>
  <circle cx="160" cy="160" r="148" fill="url(#amberRing)" filter="url(#safetyShadow)" />
  <circle cx="160" cy="160" r="138" fill="#FFFFFF" />
  <circle cx="160" cy="160" r="130" fill="url(#iso45001Grad)" />
  <circle cx="160" cy="160" r="124" fill="none" stroke="#FDE68A" stroke-width="2" stroke-dasharray="6,4" />
  
  <g transform="translate(160, 85)" text-anchor="middle">
    <!-- Safety Hardhat / Cross Icon -->
    <path d="M-18 8 Q-20 -12 0 -16 Q20 -12 18 8 Z" fill="#F59E0B" />
    <path d="M-24 8 L24 8 L24 14 L-24 14 Z" fill="#D97706" rx="3" />
    <path d="M-4 -2 L4 -2 L4 -8 L-4 -8 Z" fill="#FFFFFF" />
    <path d="M-8 -5 L8 -5 L8 -5 L-8 -5 Z" fill="#FFFFFF" />
  </g>
  
  <text x="160" y="152" text-anchor="middle" fill="#FFFFFF" font-family="'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-weight="900" font-size="34" letter-spacing="1">ISO</text>
  <text x="160" y="190" text-anchor="middle" fill="#FDE68A" font-family="'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-weight="800" font-size="30" letter-spacing="2">45001:2018</text>
  
  <rect x="55" y="208" width="210" height="26" rx="13" fill="#7C2D12" stroke="#FBBF24" stroke-width="1.5" />
  <text x="160" y="225" text-anchor="middle" fill="#FFFFFF" font-family="'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-weight="700" font-size="11" letter-spacing="1.5">OCCUPATIONAL HEALTH &amp; SAFETY</text>
  
  <text x="160" y="254" text-anchor="middle" fill="#FED7AA" font-family="'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-weight="600" font-size="10" letter-spacing="1">ZERO ACCIDENT CULTURE (K3)</text>
  <text x="160" y="270" text-anchor="middle" fill="#E2E8F0" font-family="'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-weight="500" font-size="9">PT NIHON PLAST INDONESIA</text>
</svg>
SVG;

// 5. TKDN Indonesian Domestic Value Certification SVG
$tkdnSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 320" width="100%" height="100%">
  <defs>
    <linearGradient id="tkdnGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#991B1B" />
      <stop offset="50%" stop-color="#450A0A" />
      <stop offset="100%" stop-color="#1C0505" />
    </linearGradient>
    <linearGradient id="redRing" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#EF4444" />
      <stop offset="50%" stop-color="#B91C1C" />
      <stop offset="100%" stop-color="#7F1D1D" />
    </linearGradient>
    <filter id="tkdnShadow" x="-10%" y="-10%" width="120%" height="120%">
      <feDropShadow dx="0" dy="6" stdDeviation="8" flood-color="#450A0A" flood-opacity="0.35" />
    </filter>
  </defs>
  <circle cx="160" cy="160" r="148" fill="url(#redRing)" filter="url(#tkdnShadow)" />
  <circle cx="160" cy="160" r="138" fill="#FFFFFF" />
  <circle cx="160" cy="160" r="130" fill="url(#tkdnGrad)" />
  <circle cx="160" cy="160" r="124" fill="none" stroke="#FCA5A5" stroke-width="2" stroke-dasharray="6,4" />
  
  <g transform="translate(160, 85)" text-anchor="middle">
    <!-- Indonesian Shield Flag Motif -->
    <path d="M0 -15 L18 -5 L18 10 Q18 24 0 32 Q-18 24 -18 10 L-18 -5 Z" fill="#DC2626" stroke="#FFFFFF" stroke-width="2" />
    <path d="M-16 8 Q-16 22 0 30 Q16 22 16 8 L16 8 L-16 8 Z" fill="#FFFFFF" />
  </g>
  
  <text x="160" y="152" text-anchor="middle" fill="#FFFFFF" font-family="'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-weight="900" font-size="34" letter-spacing="1">TKDN</text>
  <text x="160" y="190" text-anchor="middle" fill="#FCA5A5" font-family="'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-weight="800" font-size="28" letter-spacing="1">78.4% KEMENPERIN</text>
  
  <rect x="45" y="208" width="230" height="26" rx="13" fill="#7F1D1D" stroke="#FCA5A5" stroke-width="1.5" />
  <text x="160" y="225" text-anchor="middle" fill="#FFFFFF" font-family="'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-weight="700" font-size="10.5" letter-spacing="1">TINGKAT KOMPONEN DALAM NEGERI</text>
  
  <text x="160" y="254" text-anchor="middle" fill="#FECACA" font-family="'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-weight="600" font-size="10" letter-spacing="1">REPUBLIK INDONESIA RESMI</text>
  <text x="160" y="270" text-anchor="middle" fill="#E2E8F0" font-family="'Segoe UI', Roboto, Helvetica, Arial, sans-serif" font-weight="500" font-size="9">PT NIHON PLAST INDONESIA</text>
</svg>
SVG;

// Save files
file_put_contents($dir . 'cert_iatf_16949.svg', $iatfSvg);
file_put_contents($dir . 'cert_iso_9001.svg', $iso9001Svg);
file_put_contents($dir . 'cert_iso_14001.svg', $iso14001Svg);
file_put_contents($dir . 'cert_iso_45001.svg', $iso45001Svg);
file_put_contents($dir . 'cert_tkdn_kemenperin.svg', $tkdnSvg);

echo "Successfully wrote 5 certification SVG badges to uploads/certifications/\n";

// Update database records
$conn->query("UPDATE certifications SET image = 'uploads/certifications/cert_iatf_16949.svg' WHERE name LIKE '%16949%' OR id = 1");
$conn->query("UPDATE certifications SET image = 'uploads/certifications/cert_iso_9001.svg' WHERE name LIKE '%9001%' OR id = 2");
$conn->query("UPDATE certifications SET image = 'uploads/certifications/cert_iso_14001.svg' WHERE name LIKE '%14001%' OR id = 3");
$conn->query("UPDATE certifications SET image = 'uploads/certifications/cert_iso_45001.svg' WHERE name LIKE '%45001%' OR id = 4");
$conn->query("UPDATE certifications SET image = 'uploads/certifications/cert_tkdn_kemenperin.svg' WHERE name LIKE '%TKDN%' OR id = 5");

echo "Updated certifications table with image paths.\n";
