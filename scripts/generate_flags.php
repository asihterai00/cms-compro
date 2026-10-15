<?php
$flagsDir = __DIR__ . '/../assets/img/flags';
if (!is_dir($flagsDir)) {
    mkdir($flagsDir, 0755, true);
}

// 1. Indonesia
$idSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 480" width="22" height="16" class="flag-icon">
  <g fill-rule="evenodd" stroke-width="1pt">
    <rect width="640" height="240" fill="#e70011"/>
    <rect y="240" width="640" height="240" fill="#ffffff"/>
  </g>
</svg>
SVG;
file_put_contents($flagsDir . '/id.svg', $idSvg);

// 2. English (UK Union Jack)
$enSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 480" width="22" height="16" class="flag-icon">
  <path fill="#012169" d="M0 0h640v480H0z"/>
  <path fill="#FFF" d="m75 0 244 181L562 0h78v62L400 240l240 178v62h-80L320 301 81 480H0v-60l239-180L0 64V0h75z"/>
  <path fill="#C8102E" d="m424 288 216 153v39h-40L384 318zm17-96L641 40V0h-40L385 162zm-208 0L33 40V0h40L233 162zm0 96L33 441v39h40l160-120z"/>
  <path fill="#FFF" d="M256 0h128v480H256zM0 176h640v128H0z"/>
  <path fill="#C8102E" d="M280 0h80v480h-80zM0 200h640v80H0z"/>
</svg>
SVG;
file_put_contents($flagsDir . '/en.svg', $enSvg);

// 3. Japan (Hinomaru)
$jaSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 480" width="22" height="16" class="flag-icon">
  <rect width="640" height="480" fill="#ffffff"/>
  <circle cx="320" cy="240" r="144" fill="#bc002d"/>
</svg>
SVG;
file_put_contents($flagsDir . '/ja.svg', $jaSvg);

// 4. China
$zhSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 480" width="22" height="16" class="flag-icon">
  <path fill="#ee1c25" d="M0 0h640v480H0z"/>
  <polygon fill="#ffde00" points="106,64 122,112 173,112 131,142 147,190 106,160 65,190 81,142 39,112 90,112"/>
  <polygon fill="#ffde00" points="213,32 216,48 232,48 219,57 224,73 211,63 198,73 203,57 190,48 206,48"/>
  <polygon fill="#ffde00" points="256,74 254,90 270,93 255,99 257,115 246,103 234,112 242,97 231,87 247,89"/>
  <polygon fill="#ffde00" points="256,138 250,154 266,160 250,163 249,180 240,166 226,172 237,159 229,147 245,152"/>
  <polygon fill="#ffde00" points="213,181 204,195 218,204 202,204 198,220 192,205 177,208 190,198 185,184 198,192"/>
</svg>
SVG;
file_put_contents($flagsDir . '/zh.svg', $zhSvg);

// 5. South Korea
$koSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 480" width="22" height="16" class="flag-icon">
  <path fill="#fff" d="M0 0h640v480H0z"/>
  <g transform="translate(320,240)">
    <g transform="rotate(-33.69)">
      <path fill="#cd2e3a" d="M-120 0a120 120 0 0 1 240 0 60 60 0 0 1-120 0 60 60 0 0 0-120 0z"/>
      <path fill="#0047a0" d="M120 0a120 120 0 0 1-240 0 60 60 0 0 1 120 0 60 60 0 0 0 120 0z"/>
    </g>
    <g fill="#000" transform="rotate(-33.69)">
      <path d="M-180-80h20v160h-20zm30 0h20v160h-20zm30 0h20v160h-20z"/>
      <path d="M100-80h20v70h-20zm0 90h20v70h-20zm30-90h20v70h-20zm0 90h20v70h-20zm30-90h20v70h-20zm0 90h20v70h-20z"/>
    </g>
  </g>
</svg>
SVG;
file_put_contents($flagsDir . '/ko.svg', $koSvg);

echo "All 5 vector flags generated successfully in $flagsDir\n";
