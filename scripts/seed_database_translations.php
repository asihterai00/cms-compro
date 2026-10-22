<?php
/**
 * PT Nihon Plast Indonesia - Database Translation Seeder
 * Populates `content_translations` table with authentic Tier-1 Automotive translations
 * Languages: English (en), Japanese (ja), Chinese (zh), Korean (ko)
 * 100% Free & Open-Source, zero paid external APIs
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/translation_helpers.php';

echo "========================================================\n";
echo "PT NIHON PLAST INDONESIA - DATABASE TRANSLATION SEEDER\n";
echo "========================================================\n\n";

$translations = [
    // ---------------------------------------------------------
    // 1. PRODUCTS
    // ---------------------------------------------------------
    'product' => [
        1 => [
            'en' => [
                'name' => 'Steering Wheel Systems',
                'description' => 'Global-standard steering wheel systems featuring high-precision genuine leather wrapping, premium polyurethane formulations, multifunction control switches, integrated heating elements, and Hands-on Detection (HOD) safety touch sensors.'
            ],
            'ja' => [
                'name' => 'ステアリングホイールシステム',
                'description' => '高精度本革巻き、高品質ポリウレタン成形、マルチファンクション制御スイッチ、ステアリングヒーター、およびハンズオン検知（HOD）安全タッチセンサーを統合した世界標準のステアリングホイールシステム。'
            ],
            'zh' => [
                'name' => '方向盘安全集成系统',
                'description' => '符合全球标准的汽车方向盘系统，采用高精度真皮包覆、优质聚氨酯成型工艺、集成多功能控制按键、方向盘加热元件及脱手检测（HOD）安全传感技术。'
            ],
            'ko' => [
                'name' => '스티어링 휠 안전 시스템',
                'description' => '고정밀 천연 가죽 래핑, 프리미엄 폴리우레탄 폼 성형, 다기능 스위치 모듈, 열선 스티어링 휠 및 핸즈온 감지(HOD) 안전 센서를 결합한 글로벌 스탠다드 조향 시스템.'
            ]
        ],
        2 => [
            'en' => [
                'name' => 'Airbag Safety Modules',
                'description' => 'Integrated high-speed airbag modules (Driver, Passenger, Side Curtain, and Knee Airbag) engineered with precision tear-seam resin covers that deploy flawlessly within milliseconds upon collision impact.'
            ],
            'ja' => [
                'name' => 'エアバッグ安全モジュール',
                'description' => '衝突時にミリ秒単位で完璧に展開する高精度テアシーム樹脂カバーを備えた統合型高速エアバッグモジュール（運転席、助手席、サイドカーテン、およびニーエアバッグ）。'
            ],
            'zh' => [
                'name' => '安全气囊防护模块',
                'description' => '一体化高速安全气囊模块（驾驶员、副驾驶、侧气帘及膝部气囊），配备精密弱化线树脂气囊罩，确保在碰撞瞬间毫秒级精准爆破展开，全方位守护乘员安全。'
            ],
            'ko' => [
                'name' => '에어백 안전 모듈 시스템',
                'description' => '충돌 시 수 밀리초 내에 오차 없이 완벽하게 전개되는 정밀 티어 심(tear-seam) 레진 커버를 적용한 통합 고속 에어백 모듈(운전석, 동승석, 사이드 커튼 및 무릎 에어백).'
            ]
        ],
        3 => [
            'en' => [
                'name' => 'Cockpit & Interior Resin Parts',
                'description' => 'High-aesthetic, millimeter-precision cockpit interior resin components: Instrument Panel Clusters, Air Conditioner Registers & Ducts, Center Console Boxes, Glove Boxes, Cup Holders, and Pillar Garnishes.'
            ],
            'ja' => [
                'name' => 'コックピット・インストルメントパネル内装樹脂部品',
                'description' => '高品位な意匠性とミリ単位の精度を誇るキャビン内装樹脂部品：インストルメントパネル、エアコン吹出口・レジスター、センターコンソールボックス、グローブボックス、カップホルダー、ピラーガーニッシュ。'
            ],
            'zh' => [
                'name' => '座舱仪表板及内饰树脂零件',
                'description' => '兼具卓越美学质感与毫米级装配精度的汽车座舱内饰件：仪表板总成、空调出风口与风道系统、中央扶手箱、手套箱、杯托总成以及立柱护板饰条。'
            ],
            'ko' => [
                'name' => '콕핏 및 인테리어 레진 정밀 부품',
                'description' => '뛰어난 감성 품질과 밀리미터 단위 정밀도를 갖춘 콕핏 인테리어 플라스틱 부품: 인스트루먼트 패널 클러스터, 공조 에어벤트 레지스터 및 덕트, 센터 콘솔 박스, 글로브 박스, 컵홀더, 필러 가니시.'
            ]
        ],
        4 => [
            'en' => [
                'name' => 'Exterior & Aerodynamic Trim',
                'description' => 'Rugged exterior resin components with high resistance against extreme weather conditions and stone chipping: Cowl Top Covers, Fender Protectors, and Noise-Damping Wheel Resonators for superior acoustic comfort.'
            ],
            'ja' => [
                'name' => '外装・エアロダイナミックトリム部品',
                'description' => '過酷な耐候性と耐チッピング性を備えた高強度外装樹脂部品：カウルトップカバー、フェンダープロテクター、およびロードノイズを低減するホイールレゾネーター。'
            ],
            'zh' => [
                'name' => '外饰及空气动力学组件',
                'description' => '具备优异耐候性与抗飞石冲击性能的高强度外饰树脂组件：风挡集雨板总成（Cowl Top）、翼子板护板以及有效降低轮胎腔体共鸣噪音的轮毂消音器（Wheel Resonator）。'
            ],
            'ko' => [
                'name' => '익스테리어 및 공기역학 트림 부품',
                'description' => '극한의 기후 조건과 주행 중 스톤칩에 견디는 고내구성 외장 부품: 카울 탑 커버, 펜더 프로텍터 및 주행 소음을 획기적으로 흡수하는 휠 레조네이터.'
            ]
        ]
    ],

    // ---------------------------------------------------------
    // 2. COMPANY PROFILE
    // ---------------------------------------------------------
    'company_profile' => [
        1 => [
            'en' => [
                'tagline' => 'Driving Safety & Precision Through Japanese Monozukuri',
                'short_description' => 'Tier-1 Global Automotive Supplier specializing in Steering Wheels, Airbag Modules, and Interior/Exterior Resin Components certified to IATF 16949, ISO 14001, and ISO 45001 standards.',
                'about_text' => 'PT Nihon Plast Indonesia is a premier Tier-1 global automotive supplier established in Karawang, West Java. Backed by Nihon Plast Co., Ltd. (Japan) since 1948, we manufacture safety-critical steering wheels, airbag systems, and high-precision resin components for leading global OEMs.',
                'vision' => 'To be the most trusted Tier-1 automotive safety and precision component partner worldwide, championing Japanese Monozukuri craftsmanship, zero-defect quality, and sustainable manufacturing.',
                'mission' => 'Deliver unmatched automotive occupant safety and interior refinement through cutting-edge technology, stringent quality management, empowerment of human talent, and environmental responsibility.'
            ],
            'ja' => [
                'tagline' => '日本のモノづくりで創る、自動車の安全と極限の精密さ',
                'short_description' => 'IATF 16949、ISO 14001、ISO 45001認証を取得した、ステアリングホイール、エアバッグモジュール、内外装樹脂部品を専門とするグローバルTier-1自動車部品メーカー。',
                'about_text' => 'PT Nihon Plast Indonesiaは、西ジャワ州カラワンに拠点を置く世界有数のTier-1自動車サプライヤーです。1948年創業の日本プラスト株式会社のDNAを受け継ぎ、世界的な自動車メーカー（OEM）向けにステアリング、エアバッグ、高精度樹脂部品を製造しています。',
                'vision' => '日本のモノづくりの精神、不良ゼロ品質、環境に配慮したサステナブルな生産を通じて、世界で最も信頼されるTier-1自動車部品パートナーを目指します。',
                'mission' => '最先端の技術革新、徹底した品質管理、人材の育成、そして環境への調和を通じて、世界中の搭乗者の安全と快適な車内空間を提供し続けます。'
            ],
            'zh' => [
                'tagline' => '承袭日本匠心精造，铸就卓越汽车安全',
                'short_description' => '全球知名Tier-1汽车零部件制造商，专业生产方向盘、安全气囊系统及内外饰树脂零件，通过IATF 16949、ISO 14001及ISO 45001权威认证。',
                'about_text' => '印度尼西亚日本塑胶有限公司（PT Nihon Plast Indonesia）坐落于西爪哇省卡拉旺工业区。依托日本母公司自1948年以来的深厚积淀，专注于为全球一线汽车品牌提供高品质方向盘、安全气囊和精密注塑零部件。',
                'vision' => '以精益求精的日本制造精神、零缺陷品质承诺及绿色环保理念，成为全球汽车制造商最值得信赖的一级安全部件核心战略伙伴。',
                'mission' => '依托先进工程制造、严格的国际质量体系及持续的人才发展，为全球驾乘人员筑牢生命安全防线，带来非凡座舱体验。'
            ],
            'ko' => [
                'tagline' => '일본 모노즈쿠리 장인정신으로 완성하는 차량 안전과 정밀 공학',
                'short_description' => 'IATF 16949, ISO 14001, ISO 45001 글로벌 인증을 획득한 스티어링 휠, 에어백 모듈 및 내외장 수지 부품 전문 글로벌 Tier-1 자동차 부품 기업.',
                'about_text' => 'PT Nihon Plast Indonesia는 인도네시아 서자바주 카라왕 산업단지에 위치한 대표적인 글로벌 Tier-1 자동차 공급업체입니다. 1948년 창립된 일본 니혼플라스트의 독보적인 기술력을 바탕으로 세계 유수의 완성차 업체를 위한 안전 핵심 부품을 생산합니다.',
                'vision' => '철저한 장인정신과 무결점 품질, 지속 가능한 친환경 제조를 선도하여 전 세계 완성차 고객이 가장 신뢰하는 Tier-1 파트너로 도약한다.',
                'mission' => '최첨단 사출 성형 기술과 엄격한 품질 관리 체계, 인재 육성을 통하여 모든 운전자와 탑승자의 생명과 안전을 책임지는 최고의 부품을 공급한다.'
            ]
        ]
    ],

    // ---------------------------------------------------------
    // 3. PAGE BANNERS
    // ---------------------------------------------------------
    'page_banner' => [
        1 => [ // home
            'en' => ['page_title' => 'PT Nihon Plast Indonesia', 'subtitle' => 'Global Tier-1 Automotive Safety & Precision Resin Systems'],
            'ja' => ['page_title' => 'PT Nihon Plast Indonesia', 'subtitle' => 'グローバルTier-1自動車安全・高精度樹脂システム'],
            'zh' => ['page_title' => 'PT Nihon Plast Indonesia', 'subtitle' => '全球一级汽车安全与精密树脂制造系统'],
            'ko' => ['page_title' => 'PT Nihon Plast Indonesia', 'subtitle' => '글로벌 Tier-1 자동차 안전 및 정밀 수지 제조 시스템']
        ],
        2 => [ // about
            'en' => ['page_title' => 'About Our Company', 'subtitle' => 'Discover our Monozukuri heritage, corporate leadership, and global footprint since 1948.'],
            'ja' => ['page_title' => '会社案内・企業理念', 'subtitle' => '1948年の創業以来受け継がれるモノづくり精神、グローバルネットワーク、企業方針をご紹介します。'],
            'zh' => ['page_title' => '关于我们与企业传承', 'subtitle' => '深入了解我们自1948年传承至今的日本精益制造精神、全球业务布局与核心价值观。'],
            'ko' => ['page_title' => '회사 소개 및 기업 이념', 'subtitle' => '1948년부터 이어져 온 모노즈쿠리 장인정신, 글로벌 사업 네트워크 및 경영 방침을 소개합니다.']
        ],
        3 => [ // products
            'en' => ['page_title' => 'Automotive Products Portfolio', 'subtitle' => 'Steering wheel systems, airbag safety modules, and millimeter-precision resin assemblies.'],
            'ja' => ['page_title' => '製品ポートフォリオ', 'subtitle' => 'ステアリングホイール、エアバッグ安全モジュール、高精度インストルメント内装樹脂部品。'],
            'zh' => ['page_title' => '产品系列与制造解决方案', 'subtitle' => '提供方向盘总成、碰撞安全气囊模块及毫米级座舱树脂精密注塑组件。'],
            'ko' => ['page_title' => '제품 포트폴리오', 'subtitle' => '스티어링 휠 시스템, 에어백 모듈 및 고정밀 콕핏 내외장 플라스틱 어셈블리.']
        ],
        4 => [ // gallery
            'en' => ['page_title' => 'Manufacturing Gallery', 'subtitle' => 'High-resolution documentation of cleanroom production cells, robotic systems, and lab metrology.'],
            'ja' => ['page_title' => '製造現場・工場ギャラリー', 'subtitle' => 'クリーンルーム射出成形、ロボット自動化ライン、三次元測定検査の記録。'],
            'zh' => ['page_title' => '工厂车间与现场图库', 'subtitle' => '高清实景展示无尘洁净车间、工业机器人自动化单元及精密三坐标检测实验室。'],
            'ko' => ['page_title' => '제조 현장 갤러리', 'subtitle' => '클린룸 사출 생산 라인, 산업용 로봇 자동화 및 초정밀 3차원 측정 검사실 현장.']
        ],
        5 => [ // quality
            'en' => ['page_title' => 'Quality Policy & Certifications', 'subtitle' => 'IATF 16949, ISO 9001, ISO 14001, and ISO 45001 / SMK3 accreditation matrix.'],
            'ja' => ['page_title' => '品質方針・国際認証', 'subtitle' => 'IATF 16949、ISO 9001、ISO 14001、ISO 45001認証およびインドネシア法規制順守。'],
            'zh' => ['page_title' => '质量方针与权威认证', 'subtitle' => 'IATF 16949、ISO 9001、ISO 14001环境体系及ISO 45001职业健康安全认证。'],
            'ko' => ['page_title' => '품질 방침 및 글로벌 인증', 'subtitle' => 'IATF 16949, ISO 9001, ISO 14001 및 ISO 45001 인증을 통한 무결점 품질 보증.']
        ],
        6 => [ // facility
            'en' => ['page_title' => 'Plant & Advanced Machinery', 'subtitle' => '150T to 2500T injection molding tonnage, Class 8 cleanrooms, and CMM metrology.'],
            'ja' => ['page_title' => '工場設備・技術設備', 'subtitle' => '150トン〜2500トン射出成形機群、クラス8クリーンルーム、三次元測定ラボ。'],
            'zh' => ['page_title' => '工厂规模与精密装备', 'subtitle' => '150吨至2500吨全吨位注塑成型机群、十万级洁净车间及高精度CMM检测设备。'],
            'ko' => ['page_title' => '생산 시설 및 정밀 설비', 'subtitle' => '150톤~2500톤 대형 사출 성형기 라인업, 클린룸 Class 8 및 CMM 측정 시스템.']
        ],
        7 => [ // career
            'en' => ['page_title' => 'Careers & Recruitment', 'subtitle' => 'Build an inspiring career in Tier-1 automotive manufacturing with growth and global training.'],
            'ja' => ['page_title' => '採用情報・キャリア', 'subtitle' => '自動車部品の最前線でキャリアを築く。グローバル研修と充実の福利厚生制度。'],
            'zh' => ['page_title' => '人才招聘与职业发展', 'subtitle' => '加入全球汽车一级制造团队，携手卓越工程技术，享有广阔晋升与海外培训机会。'],
            'ko' => ['page_title' => '인재 채용 및 커리어', 'subtitle' => '글로벌 완성차 Tier-1 제조 현장에서 전문성을 키우고 함께 성장할 인재를 모십니다.']
        ],
        8 => [ // news
            'en' => ['page_title' => 'Corporate News & Media', 'subtitle' => 'Latest announcements, OEM awards, plant expansions, and corporate sustainability milestones.'],
            'ja' => ['page_title' => 'ニュース・企業情報', 'subtitle' => '最新のプレスリリース、主要自動車メーカーからの受賞実績、設備増強、CSR活動。'],
            'zh' => ['page_title' => '新闻资讯与企业动态', 'subtitle' => '了解最新企业公告、OEM客户杰出贡献奖、新产线投产及可持续发展里程碑。'],
            'ko' => ['page_title' => '기업 뉴스 및 공지사항', 'subtitle' => '최신 기업 동향, 완성차 고객사 품질상 수상 내역, 신규 설비 확장 소식을 전합니다.']
        ],
        9 => [ // contact
            'en' => ['page_title' => 'Contact Us & B2B RFQ', 'subtitle' => 'Submit technical engineering inquiries, B2B procurement requests, or factory visit scheduling.'],
            'ja' => ['page_title' => 'お問い合わせ・B2B見積依頼', 'subtitle' => '製品開発、B2B見積（RFQ）、工場見学、技術相談に関するお問い合わせ。'],
            'zh' => ['page_title' => '联系我们与B2B采购咨询', 'subtitle' => '提交汽车零部件B2B报价申请（RFQ）、工程技术合作洽谈或安排工厂实地考察。'],
            'ko' => ['page_title' => '문의처 및 B2B 견적 문의', 'subtitle' => '부품 개발 문의, B2B 견적 요청(RFQ) 및 공장 방문 상담을 위한 공식 연락처.']
        ]
    ],

    // ---------------------------------------------------------
    // 4. NEWS ARTICLES
    // ---------------------------------------------------------
    'news' => [
        1 => [
            'en' => [
                'title' => 'PT Nihon Plast Indonesia Launches Modernized B2B Digital Platform',
                'content' => 'In line with our commitment to transparency and seamless collaboration with global automakers, PT Nihon Plast Indonesia officially unveils our upgraded Tier-1 digital portal featuring comprehensive product specifications, technical machine matrices, and interactive compliance verification.'
            ],
            'ja' => [
                'title' => 'PT Nihon Plast Indonesia 新世代B2Bポータルサイトを公開',
                'content' => '世界的な自動車メーカーとの連携強化と情報開示の透明性向上の一環として、製品仕様、成形機マトリクス、国際認証を網羅した新しい公式B2Bポータルサイトを公開いたしました。'
            ],
            'zh' => [
                'title' => '印度尼西亚日本塑胶正式上线全新现代化B2B数字化服务平台',
                'content' => '为深化与全球各大汽车制造商的数字化协同合作，PT Nihon Plast Indonesia正式推出新一代B2B企业官网，全方位展示高精制造产品参数、注塑设备矩阵及国际合规认证体系。'
            ],
            'ko' => [
                'title' => 'PT Nihon Plast Indonesia 신규 B2B 디지털 플랫폼 공식 오픈',
                'content' => '글로벌 완성차 고객사와의 긴밀한 파트너십과 기술 정보 공유 강화를 위해, 제품 스펙, 사출 설비 사양, 품질 인증 체계를 총망라한 신규 B2B 포털을 공식 론칭하였습니다.'
            ]
        ],
        2 => [
            'en' => [
                'title' => 'Continuous Improvement & Kaizen Excellence Recognition in Karawang Plant',
                'content' => 'Our Cikampek plant operations have achieved a 99.98% first-pass yield in leather steering wheel assembly through operator empowerment, ergonomic tooling upgrades, and automated inspection vision systems.'
            ],
            'ja' => [
                'title' => 'カラワン工場でのカイゼン・継続的改善活動が卓越した成果を達成',
                'content' => 'チカンペック工場における本革ステアリング縫製工程において、作業員のスキル向上と画像自動検査システムの導入により、初回歩留まり99.98%を達成いたしました。'
            ],
            'zh' => [
                'title' => '卡拉旺制造基地全面推进精益改善（Kaizen），良品率再创新高',
                'content' => '通过优化人体工学工装夹具及引入全自动AI机器视觉检测，我司奇坎佩克工厂真皮方向盘手工缝制流水线一次装配合格率成功攀升至99.98%。'
            ],
            'ko' => [
                'title' => '카라왕 공장, 카이젠(Kaizen) 혁신 활동으로 완성도 극대화',
                'content' => '치캄펙 공장 가죽 스티어링 휠 조립 라인에서 작업 표준화 및 자동 비전 검사 시스템을 도입하여 99.98%의 초품 양품률을 달성하는 결실을 거두었습니다.'
            ]
        ],
        3 => [
            'en' => [
                'title' => 'Zero-Defect Monozukuri Manufacturing Milestone Reached',
                'content' => 'Reflecting our relentless pursuit of perfection, PT Nihon Plast Indonesia completed 1.5 million steering wheel and airbag assemblies delivered to major Japanese OEMs with zero critical field defects.'
            ],
            'ja' => [
                'title' => '不良ゼロ「モノづくり」マイルストーンを達成',
                'content' => '飽くなき品質の追求により、PT Nihon Plast Indonesiaは主要日系自動車メーカー向けに150万台のステアリングおよびエアバッグを出荷し、重大市場クレームゼロを達成しました。'
            ],
            'zh' => [
                'title' => '精益制造达成里程碑：累计交付150万套零缺陷安全转向系统',
                'content' => '秉持对品质的极致苛求，我司向各大日系知名车企累计交付150万套方向盘及安全气囊模块，始终保持重大零故障记录，赢得客户高度赞誉。'
            ],
            'ko' => [
                'title' => '무결점 모노즈쿠리 제조 마일스톤 달성',
                'content' => '완벽한 품질을 향한 지속적인 노력을 통해, PT Nihon Plast Indonesia는 일본 주요 완성차 업체에 150만 대의 스티어링 및 에어백을 공급하며 무결점 출하 기록을 달성했습니다.'
            ]
        ]
    ],

    // ---------------------------------------------------------
    // 5. CAREERS
    // ---------------------------------------------------------
    'career' => [
        1 => [
            'en' => ['position' => 'IT Infrastructure & Security Staff', 'department' => 'Information Technology'],
            'ja' => ['position' => 'ITインフラ・セキュリティ担当スタッフ', 'department' => '情報システム部'],
            'zh' => ['position' => 'IT网络基础设施与系统安全专员', 'department' => '信息技术部'],
            'ko' => ['position' => 'IT 인프라 및 정보보안 담당자', 'department' => '정보시스템부']
        ],
        2 => [
            'en' => ['position' => 'Injection Molding Production Engineer', 'department' => 'Manufacturing & Production'],
            'ja' => ['position' => '射出成形生産技術エンジニア', 'department' => '製造・生産技術部'],
            'zh' => ['position' => '注塑工艺与生产制造工程师', 'department' => '制造与生产技术部'],
            'ko' => ['position' => '사출 성형 생산기술 엔지니어', 'department' => '제조생산기술부']
        ]
    ],

    // ---------------------------------------------------------
    // 6. CERTIFICATIONS
    // ---------------------------------------------------------
    'certification' => [
        1 => [
            'en' => ['name' => 'IATF 16949:2016', 'category' => 'Automotive Quality Management Standard'],
            'ja' => ['name' => 'IATF 16949:2016', 'category' => '自動車産業品質マネジメントシステム規格'],
            'zh' => ['name' => 'IATF 16949:2016', 'category' => '国际汽车行业质量管理体系标准'],
            'ko' => ['name' => 'IATF 16949:2016', 'category' => '글로벌 자동차 품질경영시스템 표준']
        ],
        2 => [
            'en' => ['name' => 'ISO 9001:2015', 'category' => 'Quality Management Systems'],
            'ja' => ['name' => 'ISO 9001:2015', 'category' => '国際品質マネジメントシステム'],
            'zh' => ['name' => 'ISO 9001:2015', 'category' => '国际质量管理体系认证'],
            'ko' => ['name' => 'ISO 9001:2015', 'category' => '국제 품질경영시스템 인증']
        ],
        3 => [
            'en' => ['name' => 'ISO 14001:2015', 'category' => 'Environmental Management System'],
            'ja' => ['name' => 'ISO 14001:2015', 'category' => '環境マネジメントシステム規格'],
            'zh' => ['name' => 'ISO 14001:2015', 'category' => '环境管理体系认证'],
            'ko' => ['name' => 'ISO 14001:2015', 'category' => '환경경영시스템 인증']
        ],
        4 => [
            'en' => ['name' => 'ISO 45001:2018 & SMK3 PP 50/2012', 'category' => 'Occupational Health & Safety (K3)'],
            'ja' => ['name' => 'ISO 45001:2018 & SMK3 PP 50/2012', 'category' => '労働安全衛生マネジメントシステム'],
            'zh' => ['name' => 'ISO 45001:2018 & 印尼SMK3法规', 'category' => '职业健康安全管理体系'],
            'ko' => ['name' => 'ISO 45001:2018 & 인도네시아 SMK3', 'category' => '안전보건경영시스템']
        ],
        5 => [
            'en' => ['name' => 'Kemenperin & SNI National Compliance', 'category' => 'Indonesian Ministry of Industry Standard'],
            'ja' => ['name' => 'インドネシア工業省 & SNI基準適合', 'category' => 'インドネシア国家規制基準'],
            'zh' => ['name' => '印尼工业部认证及SNI国家标准', 'category' => '印尼国家强制法规合规认证'],
            'ko' => ['name' => '인도네시아 산업부 및 SNI 표준', 'category' => '인도네시아 국가 표준 규격']
        ]
    ],

    // ---------------------------------------------------------
    // 7. FACILITY MACHINERY
    // ---------------------------------------------------------
    'facility_machinery' => [
        1 => [
            'en' => ['model_brand' => 'Sumitomo / Toshiba Hydraulic Matrix', 'application' => 'Instrument Panel & Cowl Top Exterior Assemblies'],
            'ja' => ['model_brand' => '住友重機 / 東芝 油圧大型成形機群', 'application' => 'インストルメントパネル大型成形・カウルトップ外装品'],
            'zh' => ['model_brand' => '住友重工 / 东芝大型液压注塑机组', 'application' => '汽车大型仪表板总成及集雨板外饰件'],
            'ko' => ['model_brand' => '스미토모 / 도시바 유압 대형 사출기', 'application' => '대형 인스트루먼트 패널 및 카울 탑 외장 성형']
        ],
        2 => [
            'en' => ['model_brand' => 'Nissei / JSW All-Electric Precision', 'application' => 'Center Console Box & Tear-Seam Airbag Cover Modules'],
            'ja' => ['model_brand' => '日精樹脂工業 / 日本製鋼所 全電動成形機', 'application' => 'センターコンソールボックス・テアシームエアバッグカバー'],
            'zh' => ['model_brand' => '日精 / JSW日钢全电动高精密注塑机', 'application' => '中央通道扶手箱及气囊弱化撕裂线安全罩'],
            'ko' => ['model_brand' => '닛세이 / JSW 전동식 정밀 사출기', 'application' => '센터 콘솔 박스 및 에어백 테어 심 안전 커버']
        ],
        3 => [
            'en' => ['model_brand' => 'Toyo / Fanuc High-Cycle Cell', 'application' => 'Steering Wheel Core Bezel & Dual AC Register Ducts'],
            'ja' => ['model_brand' => '東洋機械金属 / ファナック 高速成形機', 'application' => 'ステアリングホイール芯金ベゼル・エアコン吹出口ルーバー'],
            'zh' => ['model_brand' => '东洋机械 / 发那科高节拍精密注塑单元', 'application' => '方向盘骨架装饰亮条及空调出风口百叶导流叶片'],
            'ko' => ['model_brand' => '토요 / 화낙 고사이클 사출 성형 셀', 'application' => '스티어링 휠 코어 베젤 및 듀얼 공조기 에어벤트 덕트']
        ],
        4 => [
            'en' => ['model_brand' => 'Fanuc Roboshot Precision Unit', 'application' => 'Micro Safety Latches, Switches & Interior Fasteners'],
            'ja' => ['model_brand' => 'ファナック ロボショット超精密成形機', 'application' => '安全スイッチ部品・微細ファスナー・内部ロック機構'],
            'zh' => ['model_brand' => '发那科 Roboshot 超精密微型注塑单元', 'application' => '精密安全开关联锁机构、微型紧固件及定位卡扣'],
            'ko' => ['model_brand' => '화낙 로보샷 초정밀 사출 유닛', 'application' => '초소형 안전 스위치 부품, 클립 및 정밀 체결 부품']
        ],
        5 => [
            'en' => ['model_brand' => 'Branson / Bielomatik Ultrasonic Cell', 'application' => 'High-Frequency 240 Hz Vibration & Ultrasonic Hot-Plate Welding'],
            'ja' => ['model_brand' => 'ブランソン / ビエロマティック 溶着機', 'application' => '高周波240Hz超音波振動溶着・ホットプレートダクト接合'],
            'zh' => ['model_brand' => '必能信 / 比洛马蒂克超声波振动摩擦焊接机', 'application' => '240Hz高频振动摩擦焊接及仪表板热板无缝融合工艺'],
            'ko' => ['model_brand' => '브랜슨 / 비엘로마틱 초음파 융착 시스템', 'application' => '고주파 240Hz 진동 및 초음파 열판 플라스틱 융착 라인']
        ]
    ]
];

$totalInserted = 0;

foreach ($translations as $entityType => $entities) {
    echo "--- Seeding Entity: {$entityType} ---\n";
    foreach ($entities as $entityId => $langMap) {
        $saved = save_entity_translations($entityType, $entityId, $langMap);
        $totalInserted += $saved;
        echo " [OK] {$entityType} ID: {$entityId} -> saved {$saved} translated fields\n";
    }
}

echo "\n========================================================\n";
echo "SEEDING COMPLETE: {$totalInserted} fields successfully saved in MySQL!\n";
echo "========================================================\n";
