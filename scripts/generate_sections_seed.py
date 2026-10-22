# -*- coding: utf-8 -*-
import json
import re

with open(r'C:\Devaron\www\cms-compro\scripts\sections_raw.json', encoding='utf-8') as f:
    secs = json.load(f)

with open(r'C:\Devaron\www\cms-compro\scripts\items_raw.json', encoding='utf-8') as f:
    items = json.load(f)

# Comprehensive dictionary for sections
# section_key -> {en: {...}, ja: {...}, zh: {...}, ko: {...}}
sec_trans = {
    'hero': {
        'en': {
            'title': 'Producing Reliable Technology for Complete Peace of Mind',
            'subtitle': 'Global Tier-1 Automotive Supplier specializing in Steering Wheels, Airbag Modules, and Precision Resin Components.',
            'badge_text': 'GLOBAL TIER-1 AUTOMOTIVE SAFETY & INTERIOR SYSTEMS',
            'button_text': 'Explore Products'
        },
        'ja': {
            'title': '確かな技術で、安心の未来を創る。',
            'subtitle': 'ステアリングホイール、エアバッグモジュール、内外装精密樹脂部品の製造に特化したグローバルTier-1自動車部品メーカー。',
            'badge_text': 'グローバル Tier-1 自動車安全・内装部品システム',
            'button_text': '製品情報を見る'
        },
        'zh': {
            'title': '以卓越可靠的技术，创造安心守护的未来',
            'subtitle': '专注于方向盘安全集成系统、安全气囊模块及内外饰高精密树脂零部件的全球Tier-1制造商。',
            'badge_text': '全球 Tier-1 汽车安全与内饰系统',
            'button_text': '探索核心产品'
        },
        'ko': {
            'title': '신뢰할 수 있는 기술력으로 안전한 미래를 창조합니다',
            'subtitle': '스티어링 휠 시스템, 에어백 모듈 및 정밀 수지 부품을 전문 제조하는 글로벌 Tier-1 자동차 부품 기업.',
            'badge_text': '글로벌 Tier-1 자동차 안전 및 내장재 시스템',
            'button_text': '주요 제품 둘러보기'
        }
    },
    'stats': {
        'en': {
            'title': 'Plant Achievements & Industrial Credentials',
            'subtitle': 'Precision Automotive Standards',
            'badge_text': 'CREDENTIALS',
            'button_text': ''
        },
        'ja': {
            'title': '工場の実績と技術認証',
            'subtitle': '世界基準のモノづくり',
            'badge_text': '主要実績',
            'button_text': ''
        },
        'zh': {
            'title': '工厂制造实力与国际认证成果',
            'subtitle': '世界级精密汽车标准',
            'badge_text': '制造资质',
            'button_text': ''
        },
        'ko': {
            'title': '공장 생산 실적 및 글로벌 인증 역량',
            'subtitle': '세계적 수준의 자동차 제조 기준',
            'badge_text': '공식 실적',
            'button_text': ''
        }
    },
    'oem_trust': {
        'en': {
            'title': 'Trusted Tier-1 Supplier to Leading Automotive OEMs',
            'subtitle': 'Direct supplier to global automotive manufacturers',
            'badge_text': 'TIER-1 TRUSTED SUPPLIER TO GLOBAL & DOMESTIC OEMS',
            'button_text': ''
        },
        'ja': {
            'title': '世界の主要完成車メーカー（OEM）から選ばれる信頼のモノづくり',
            'subtitle': 'トヨタ、ホンダ、日産、ダイハツ、三菱、スズキ等のOEMへ直接納入',
            'badge_text': '国内外主要自動車メーカーのTier-1パートナー',
            'button_text': ''
        },
        'zh': {
            'title': '深获全球与印尼各大汽车主机厂（OEM）信赖的Tier-1伙伴',
            'subtitle': '直接为丰田、本田、日产、大发、三菱、铃木等主机厂配套供货',
            'badge_text': '全球与国内主机厂认证Tier-1供应商',
            'button_text': ''
        },
        'ko': {
            'title': '글로벌 및 주요 완성차 OEM이 신뢰하는 Tier-1 파트너',
            'subtitle': '도요타, 혼다, 닛산, 다이하쓰, 미쓰비시, 스즈키 등 주요 메이커 직납 공급',
            'badge_text': '글로벌 완성차 OEM 공식 Tier-1 공급사',
            'button_text': ''
        }
    },
    'about_intro': {
        'en': {
            'title': 'Japanese Monozukuri Precision at the Heart of Indonesian Automotive Industry',
            'subtitle': 'Driving Safety & Precision Through Japanese Monozukuri',
            'badge_text': 'COMPANY OVERVIEW',
            'button_text': 'Explore Corporate Profile'
        },
        'ja': {
            'title': 'インドネシア自動車産業の拠点で息づく日本プラストのモノづくり精神',
            'subtitle': '確かな技術と安全性への妥協なき挑戦',
            'badge_text': '企業概要',
            'button_text': '会社概要の詳細を見る'
        },
        'zh': {
            'title': '融合日本精密制造精神，深耕印尼现代汽车工业枢纽',
            'subtitle': '以匠心精神引领汽车安全与高精零部件制造',
            'badge_text': '公司概况',
            'button_text': '查看企业完整档案'
        },
        'ko': {
            'title': '인도네시아 자동차 산업의 중심에서 실현하는 일본 모노즈쿠리 정밀 기술',
            'subtitle': '타협 없는 안전성과 정밀 기술의 융합',
            'badge_text': '기업 개요',
            'button_text': '회사 소개 자세히 보기'
        }
    },
    'vehicle_explorer': {
        'en': {
            'title': 'Vehicle Component Application Map',
            'subtitle': 'Click each component area below to explore how Nihon Plast precision technology integrates into modern automobile cabins.',
            'badge_text': 'VEHICLE INTEGRATION MAP',
            'button_text': ''
        },
        'ja': {
            'title': '車載コンポーネント展開マップ',
            'subtitle': '下記のコンポーネント部位をクリックすると、最新の自動車キャビンに統合された日本プラストの精密技術をご覧いただけます。',
            'badge_text': '車両搭載部位マップ',
            'button_text': ''
        },
        'zh': {
            'title': '整车应用组件全景分布图',
            'subtitle': '点击下方不同组件区域，探索日本精塑精密技术在现代汽车座舱中的集成应用。',
            'badge_text': '整车技术分布图',
            'button_text': ''
        },
        'ko': {
            'title': '차량 탑재 부품 전개도',
            'subtitle': '아래 부품 영역을 클릭하시면 현대 자동차 캐빈에 통합된 니혼 플라스트의 정밀 기술을 확인하실 수 있습니다.',
            'badge_text': '차량 적용 맵',
            'button_text': ''
        }
    },
    'products_grid': {
        'en': {
            'title': 'Automotive Component Systems',
            'subtitle': 'High-precision interior resin parts, airbag occupant protection modules, and OEM-grade steering wheels with stringent compliance.',
            'badge_text': 'PRODUCT PORTFOLIO EXCELLENCE',
            'button_text': 'View All Product Lines'
        },
        'ja': {
            'title': '自動車部品システム・製品ラインナップ',
            'subtitle': '厳格な品質基準に適合した高精度樹脂内装部品、乗員保護エアバッグモジュール、OEM純正品質のステアリングホイール。',
            'badge_text': '卓越した製品ポートフォリオ',
            'button_text': 'すべての製品ラインを見る'
        },
        'zh': {
            'title': '汽车关键零部件系统产品阵容',
            'subtitle': '符合严格主机厂质量标准的高精度树脂内饰件、乘员保护安全气囊模块及原厂品质方向盘系统。',
            'badge_text': '卓越核心产品组合',
            'button_text': '查看全部产品系列'
        },
        'ko': {
            'title': '자동차 부품 시스템 및 제품 라인업',
            'subtitle': '엄격한 글로벌 품질 기준을 충족하는 고정밀 수지 내장재, 탑승자 보호 에어백 모듈 및 정품 규격 스티어링 휠.',
            'badge_text': '핵심 제품 포트폴리오',
            'button_text': '모든 제품 라인 보기'
        }
    },
    'certifications_grid': {
        'en': {
            'title': 'International Standards & Indonesian Regulatory Compliance',
            'subtitle': 'PT Nihon Plast Indonesia operates in full compliance with global automotive quality standards (IATF 16949) and Indonesian ministerial regulations.',
            'badge_text': 'INTERNATIONAL STANDARDS & COMPLIANCE',
            'button_text': 'Learn Quality Policy'
        },
        'ja': {
            'title': '品質マネジメント規格認証・現地法規制への適合',
            'subtitle': 'PT Nihon Plast Indonesia は、グローバル自動車品質基準 IATF 16949 およびインドネシア工業省（Kemenperin）、環境林業省（KLHK）、労働省（Kemnaker）の法令要件を厳格に遵守しています。',
            'badge_text': '国際規格認証および国内規制遵守',
            'button_text': '品質方針とISO認証の詳細'
        },
        'zh': {
            'title': '国际汽车质量标准与印尼国家法规合规体系',
            'subtitle': '全面符合全球汽车工业质量管理体系（IATF 16949）及印尼工业部、环境部、劳工部严苛监管法规。',
            'badge_text': '国际认证与法规合规',
            'button_text': '了解质量方针与认证'
        },
        'ko': {
            'title': '국제 품질 인증 및 인도네시아 정부 규제 준수',
            'subtitle': 'PT Nihon Plast Indonesia는 글로벌 자동차 산업 품질 표준(IATF 16949) 및 인도네시아 산업부, 환경부, 노동부 법률 요건을 철저히 준수합니다.',
            'badge_text': '국제 규격 인증 및 규제 준수',
            'button_text': '품질 방침 및 인증 보기'
        }
    },
    'quality_pillars': {
        'en': {
            'title': 'Quality Commitment & Zero Defect Culture',
            'subtitle': 'Every component delivered to OEMs complies with rigorous safety and durability standards.',
            'badge_text': 'ZERO DEFECT MONOZUKURI',
            'button_text': ''
        },
        'ja': {
            'title': '徹底した品質へのこだわりとゼロディフェクト',
            'subtitle': '工場から出荷されるすべての製品は、ドライバーと乗員の命を守るため厳格な品質検査をクリアしています。',
            'badge_text': '日本のモノづくり精神',
            'button_text': ''
        },
        'zh': {
            'title': '坚守品质底线与零缺陷卓越理念',
            'subtitle': '出厂的每一件产品均经过多重高精度质量检测，全力守护驾驶员与乘客的生命安全。',
            'badge_text': '日本精益匠心品质',
            'button_text': ''
        },
        'ko': {
            'title': '타협 없는 무결점 품질과 제로 디펙트 철학',
            'subtitle': '출고되는 모든 제품은 운전자와 동승자의 생명을 지키기 위해 엄격한 다단계 품질 검증을 거칩니다.',
            'badge_text': '일본 모노즈쿠리 장인정신',
            'button_text': ''
        }
    },
    'facility_preview': {
        'en': {
            'title': 'Precision Manufacturing Facility in Cikampek, Karawang',
            'subtitle': 'Integrating 36+ high-precision injection molding presses (150T–2,500T), ISO Class 8 Cleanroom Assembly, and 5S testing labs.',
            'badge_text': 'MANUFACTURING FACILITY',
            'button_text': 'Explore Manufacturing Facility'
        },
        'ja': {
            'title': 'チカンペック精密製造工場（西ジャワ州カラワン）',
            'subtitle': '150T〜2500Tの超精密射出成形機群、クラス8クリーンルーム組立棟、5S管理体制を完備した総合製造拠点。',
            'badge_text': '最先端生産拠点',
            'button_text': '生産設備・工場紹介を見る'
        },
        'zh': {
            'title': '西爪哇卡拉旺芝坎佩精密制造基地',
            'subtitle': '配备36+台150T至2500T高吨位伺服注塑机集群、Class 8级无尘装配车间及5S标准化实验室。',
            'badge_text': '现代化先进制造基地',
            'button_text': '探索完整工厂设施'
        },
        'ko': {
            'title': '치캄펙 카라왕 고정밀 제조 시설',
            'subtitle': '150T~2500T 고정밀 사출기 36대 이상, ISO Class 8 클린룸 조립 라인 및 5S 품질 검사 연구소를 통합 운영합니다.',
            'badge_text': '최첨단 제조 시설',
            'button_text': '공장 시설 전체 둘러보기'
        }
    },
    'esg_sustainability': {
        'en': {
            'title': 'Green Monozukuri: Automotive Sustainability Commitment',
            'subtitle': 'Targeting carbon neutrality through solar PV installations, eco-friendly recycled resins, and systematic waste minimization.',
            'badge_text': 'GREEN MONOZUKURI',
            'button_text': ''
        },
        'ja': {
            'title': 'グリーン・モノづくり：持続可能な環境共生型生産',
            'subtitle': '屋上太陽光発電（ソーラーPV）、環境配慮型再生樹脂の活用、徹底した産業廃棄物削減によりカーボンニュートラルを推進。',
            'badge_text': '環境への取り組み（ESG）',
            'button_text': ''
        },
        'zh': {
            'title': '绿色精益制造：汽车制造的可持续发展承诺',
            'subtitle': '通过屋顶太阳能光伏发电、环保循环再生树脂应用以及工业废弃物减量化，全面推进碳中和目标。',
            'badge_text': '绿色制造与ESG',
            'button_text': ''
        },
        'ko': {
            'title': '그린 모노즈쿠리: 지속 가능한 친환경 자동차 제조',
            'subtitle': '루프탑 태양광 발전 설비 도입, 친환경 재활용 수지 활용 및 폐기물 감축을 통해 탄소 중립을 적극 실천합니다.',
            'badge_text': '지속가능경영 및 ESG',
            'button_text': ''
        }
    },
    'gallery_preview': {
        'en': {
            'title': 'Plant Activities & Facility Showcase',
            'subtitle': 'High-resolution photographic documentation of automotive precision manufacturing, 5S culture, and factory facilities.',
            'badge_text': 'PHOTO GALLERY',
            'button_text': 'View Full Gallery'
        },
        'ja': {
            'title': 'フォトギャラリー：生産現場と最新鋭設備',
            'subtitle': '自動車安全部品の製造風景、クラス8クリーンルーム、厳格な品質検査工程の記録写真。',
            'badge_text': '生産現場フォトギャラリー',
            'button_text': 'ギャラリーを見る'
        },
        'zh': {
            'title': '工厂车间现场与尖端设备影集',
            'subtitle': '真实记录汽车零部件精密注塑、无尘车间装配及严格质检流程的高清实景照片。',
            'badge_text': '生产制造实景画册',
            'button_text': '查看实景照片画册'
        },
        'ko': {
            'title': '공장 활동 및 시설 포토 갤러리',
            'subtitle': '자동차 정밀 부품 제조 현장, 클린룸 조립 공정 및 엄격한 품질 검사 프로세스를 담은 고해상도 기록입니다.',
            'badge_text': '생산 시설 사진 갤러리',
            'button_text': '갤러리 전체 보기'
        }
    },
    'news_preview': {
        'en': {
            'title': 'Company News & Corporate Announcements',
            'subtitle': 'Follow the latest developments in plant expansion, quality accreditations, and corporate milestones.',
            'badge_text': 'LATEST UPDATES',
            'button_text': 'View All News'
        },
        'ja': {
            'title': '企業ニュース・インフォメーション',
            'subtitle': '工場設備増強、品質認証の取得、CSR活動など、PT Nihon Plast Indonesia の最新動向をお知らせします。',
            'badge_text': '最新ニュース',
            'button_text': 'ニュース一覧を見る'
        },
        'zh': {
            'title': '企业新闻与官方资讯公告',
            'subtitle': '及时了解PT Nihon Plast Indonesia在工厂扩建、品质认证、技术突破及社会责任方面的最新动态。',
            'badge_text': '最新资讯',
            'button_text': '浏览全部新闻公告'
        },
        'ko': {
            'title': '기업 뉴스 및 공식 공지사항',
            'subtitle': '공장 설비 확장, 품질 인증 취득, 사회공헌 활동 등 당사의 최신 기업 소식을 전해드립니다.',
            'badge_text': '최신 뉴스',
            'button_text': '모든 뉴스 보기'
        }
    },
    'contact_cta': {
        'en': {
            'title': 'Ready to Partner with a World-Class Tier-1 Manufacturer?',
            'subtitle': 'Contact our technical sales team at Indotaisei Industrial Estate Cikampek for steering wheels, airbag systems, and precision interior resin sourcing.',
            'badge_text': 'OEM B2B SOURCING & TECHNICAL RFQ',
            'button_text': 'Submit B2B RFQ Inquiry'
        },
        'ja': {
            'title': '世界水準のTier-1メーカーとパートナーシップを結びませんか？',
            'subtitle': 'ステアリングホイール、エアバッグモジュール、高精度樹脂部品の調達・技術相談は、チカンペック工場へお気軽にお問い合わせください。',
            'badge_text': 'B2B見積依頼・技術相談',
            'button_text': 'B2B見積・相談を申し込む'
        },
        'zh': {
            'title': '准备好与具备世界一流地位的Tier-1制造专家合作了吗？',
            'subtitle': '欢迎联系位于芝坎佩印多泰成工业区的技术商务团队，探讨零部件配套采购、技术规格定制及工厂实地考察事宜。',
            'badge_text': 'OEM业务对接与技术报价',
            'button_text': '提交B2B采购意向咨询'
        },
        'ko': {
            'title': '세계적 수준의 Tier-1 파트너와 함께하시겠습니까?',
            'subtitle': '스티어링 휠, 에어백 모듈, 정밀 수지 부품 공급 및 기술 문의는 인도타이세이 치캄펙 공장 기술영업팀으로 문의해 주십시오.',
            'badge_text': 'OEM B2B 견적 및 기술 문의',
            'button_text': 'B2B 견적 문의 제출'
        }
    },
    'about_dna': {
        'en': {
            'title': 'Dedication to Safety & Global Monozukuri Precision',
            'subtitle': 'Founded as a pivotal subsidiary of Nihon Plast Co., Ltd. (Japan), supplying Japanese engineering excellence to the ASEAN automotive corridor.',
            'badge_text': 'MONOZUKURI HERITAGE',
            'button_text': ''
        },
        'ja': {
            'title': '安全への誓いと日本のモノづくり精神の継承',
            'subtitle': '日本プラスト株式会社（本社：静岡県）の主要海外拠点として、妥協なき日本品質をアセアン自動車産業へ供給。',
            'badge_text': 'モノづくりDNA',
            'button_text': ''
        },
        'zh': {
            'title': '矢志追求极致安全与全球精益制造精神',
            'subtitle': '作为日本精塑株式会社（日本静冈）核心海外子公司，将卓越日本制造工艺注入东盟汽车产业走廊。',
            'badge_text': '精益制造基因',
            'button_text': ''
        },
        'ko': {
            'title': '타협 없는 안전성과 글로벌 모노즈쿠리 정밀 기술에 대한 헌신',
            'subtitle': '일본 플라스트 주식회사(본사: 시즈오카)의 핵심 해외 거점으로서 최고의 일본 정밀 제조 품질을 아세안 시장에 공급합니다.',
            'badge_text': '모노즈쿠리 유산',
            'button_text': ''
        }
    },
    'about_vision_mission': {
        'en': {
            'title': 'Vision, Mission & Fundamental Values',
            'subtitle': 'Guiding our daily operations, technological investments, and partnerships across the global automotive landscape.',
            'badge_text': 'CORPORATE PHILOSOPHY',
            'button_text': ''
        },
        'ja': {
            'title': '企業理念・ビジョン・ミッション',
            'subtitle': '日々の生産活動、技術投資、そして世界中の自動車メーカーとの信頼関係を支える確固たる企業指針。',
            'badge_text': '企業理念',
            'button_text': ''
        },
        'zh': {
            'title': '企业愿景、使命与核心价值观',
            'subtitle': '指引我们日常精益运营、前沿技术投资以及与全球汽车主机厂深化伙伴关系的根本准则。',
            'badge_text': '企业理念',
            'button_text': ''
        },
        'ko': {
            'title': '기업 비전, 사명 및 핵심 가치',
            'subtitle': '글로벌 자동차 시장에서 당사의 일상적인 운영, 기술 투자 및 신뢰 기반 파트너십을 이끄는 기본 지침입니다.',
            'badge_text': '기업 경영 철학',
            'button_text': ''
        }
    },
    'about_global_footprint': {
        'en': {
            'title': 'Global Footprint of Nihon Plast Co., Ltd.',
            'subtitle': 'Seamless synergy across manufacturing plants and R&D design centers spanning Japan, Indonesia, Thailand, China, Mexico, and North America.',
            'badge_text': 'GLOBAL NETWORK',
            'button_text': ''
        },
        'ja': {
            'title': '日本プラストのグローバルネットワーク',
            'subtitle': '日本、インドネシア、タイ、中国、メキシコ、北米に広がる生産・開発拠点の強固なシナジー。',
            'badge_text': 'グローバル拠点',
            'button_text': ''
        },
        'zh': {
            'title': '日本精塑全球协同制造与研发网络',
            'subtitle': '横跨日本、印尼、泰国、中国、墨西哥和北美的全球化制造基地与研发中心深度协同。',
            'badge_text': '全球布局',
            'button_text': ''
        },
        'ko': {
            'title': '니혼 플라스트 글로벌 네트워크',
            'subtitle': '일본, 인도네시아, 태국, 중국, 멕시코, 북미 전역에 걸친 제조 공장과 R&D 디자인 센터의 유기적 시너지.',
            'badge_text': '글로벌 네트워크',
            'button_text': ''
        }
    },
    'about_datasheet': {
        'en': {
            'title': 'Corporate Datasheet & Plant Metrics',
            'subtitle': 'Verified operational parameters, plant footprint, installed press fleet, and compliance certifications.',
            'badge_text': 'CORPORATE DATASHEET',
            'button_text': ''
        },
        'ja': {
            'title': '会社概要データシート',
            'subtitle': '資本金、従業員数、敷地面積、保有設備、主要認証規格などの公式ファクトシート。',
            'badge_text': '企業データシート',
            'button_text': ''
        },
        'zh': {
            'title': '企业基本数据与工厂运营指标',
            'subtitle': '经权威认证的注册资本、员工人数、厂区面积、设备台数及各项国际合规认证一览。',
            'badge_text': '企业官方数据表',
            'button_text': ''
        },
        'ko': {
            'title': '기업 데이터 시트 및 공장 현황',
            'subtitle': '자본금, 임직원 수, 공장 면적, 보유 설비 대수 및 주요 국제 인증에 관한 공식 요약입니다.',
            'badge_text': '기업 데이터 시트',
            'button_text': ''
        }
    },
    'about_cta': {
        'en': {
            'title': 'Interested in Learning More About Our Capabilities?',
            'subtitle': 'Tour our precision machinery fleet or review our IATF 16949 quality management certifications.',
            'badge_text': 'EXPERIENCE OUR EXCELLENCE',
            'button_text': 'Review Facility & Machinery'
        },
        'ja': {
            'title': '当社の生産設備と品質管理体制についてさらに詳しく知りたい方へ',
            'subtitle': '高精度成形設備の見学や、IATF 16949認証をはじめとする品質保証体制の確認はお気軽にご相談ください。',
            'badge_text': '優れた技術力',
            'button_text': '生産設備・機械を見る'
        },
        'zh': {
            'title': '希望深入了解我们的制造工艺与品质体系？',
            'subtitle': '欢迎考察我们的高精密注塑设备集群，或查阅IATF 16949国际汽车质量体系认证资质。',
            'badge_text': '感受卓越工艺',
            'button_text': '查阅生产设备集群'
        },
        'ko': {
            'title': '당사의 제조 역량과 품질 관리에 대해 더 자세히 알고 싶으신가요?',
            'subtitle': '고정밀 사출기 라인을 둘러보시거나 IATF 16949 국제 품질 인증 시스템을 직접 확인해 보십시오.',
            'badge_text': '탁월한 기술력 확인',
            'button_text': '설비 및 공장 검토'
        }
    },
    'facility_plant_overview': {
        'en': {
            'title': 'Global Tier-1 Automotive Manufacturing Facility',
            'subtitle': 'Built in accordance with strict Nihon Plast Co., Ltd. (Japan) corporate standards, encompassing 28,500 m² site area and 18,200 m² facility footprint.',
            'badge_text': 'Indotaisei Industrial Estate Cikampek',
            'button_text': ''
        },
        'ja': {
            'title': 'グローバルTier-1基準を満たす最新鋭自動車部品製造工場',
            'subtitle': '日本プラスト株式会社（本社）の厳格な建築・防塵基準に基づき設計。敷地面積28,500m²、延床面積18,200m²の広大な生産能力。',
            'badge_text': 'インドタイセイ工業団地（チカンペック）',
            'button_text': ''
        },
        'zh': {
            'title': '符合全球Tier-1严苛标准的现代化汽车零部件制造基地',
            'subtitle': '严格遵循日本精塑株式会社本部高标准规程设计建造，占地面积28,500平方米，建筑面积18,200平方米。',
            'badge_text': '芝坎佩印多泰成工业区',
            'button_text': ''
        },
        'ko': {
            'title': '글로벌 Tier-1 기준을 충족하는 첨단 자동차 부품 제조 공장',
            'subtitle': '일본 플라스트 주식회사의 엄격한 설비 규격에 따라 설계되었으며 부지 면적 28,500m², 연면적 18,200m²의 규모를 자랑합니다.',
            'badge_text': '인도타이세이 치캄펙 산업단지',
            'button_text': ''
        }
    },
    'facility_capabilities': {
        'en': {
            'title': 'Pioneering Manufacturing Technologies',
            'subtitle': 'Combining high-tonnage automated injection molding systems with Takumi artisan hand-craftsmanship.',
            'badge_text': 'Production Capabilities',
            'button_text': ''
        },
        'ja': {
            'title': '高精度成形と匠の技を融合した製造技術',
            'subtitle': '最新鋭の大型高圧自動射出成形技術と、熟練職人による手縫いレザー等のクラフトマンシップが融合。',
            'badge_text': '生産ラインのコア技術',
            'button_text': ''
        },
        'zh': {
            'title': '行业领先的核心制造工艺与技术',
            'subtitle': '将自动化高吨位高压注塑系统与手缝真皮方向盘等匠人手工艺完美融合。',
            'badge_text': '核心生产能力',
            'button_text': ''
        },
        'ko': {
            'title': '선도적인 정밀 부품 제조 기술',
            'subtitle': '대형 고압 자동 사출 성형 시스템과 숙련된 장인의 정밀 수작업(타쿠미 크래프트맨십)을 결합했습니다.',
            'badge_text': '핵심 생산 역량',
            'button_text': ''
        }
    },
    'facility_machinery_intro': {
        'en': {
            'title': 'Machine Specifications & Capacity Matrix',
            'subtitle': 'Overview of primary production injection machines ranging from 150-ton to 2,500-ton capacity operating in Cikampek Karawang.',
            'badge_text': 'Technical Machinery Matrix',
            'button_text': ''
        },
        'ja': {
            'title': '主要成形設備スペック・生産能力マトリクス',
            'subtitle': 'チカンペック工場で稼働する150トン〜2,500トンまでの主要射出成形機および自動化ライン一覧。',
            'badge_text': '主要保有設備一覧',
            'button_text': ''
        },
        'zh': {
            'title': '生产机台规格与制造产能矩阵',
            'subtitle': '芝坎佩工厂稳定运行的150吨至2500吨核心伺服注塑成型机台与自动化生产系统概览。',
            'badge_text': '设备技术矩阵',
            'button_text': ''
        },
        'ko': {
            'title': '주요 기계 제원 및 생산 능력 매트릭스',
            'subtitle': '치캄펙 카라왕 공장에서 가동 중인 150톤부터 2,500톤까지의 핵심 사출 성형기 및 자동화 설비 목록입니다.',
            'badge_text': '설비 기술 매트릭스',
            'button_text': ''
        }
    },
    'facility_cleanroom': {
        'en': {
            'title': 'Airbag Module Assembly in ISO Class 8 Cleanroom',
            'subtitle': 'Positive-pressure environment equipped with airborne particle filters and electrostatic discharge (ESD) floor systems.',
            'badge_text': 'Cleanroom ISO 14644-1',
            'button_text': ''
        },
        'ja': {
            'title': 'ISOクラス8陽圧クリーンルームでのエアバッグ組立',
            'subtitle': '浮遊微粒子管理と静電気対策（ESD）を徹底した陽圧環境下で、極めて高い信頼性が求められる安全部品を組み立て。',
            'badge_text': 'クリーンルーム（ISO 14644-1）',
            'button_text': ''
        },
        'zh': {
            'title': 'ISO Class 8级无尘净化车间安全气囊装配',
            'subtitle': '配备空气微粒过滤正压系统与防静电（ESD）接地地坪，确保生命攸关安全部件的零污染装配。',
            'badge_text': 'ISO 14644-1洁净室',
            'button_text': ''
        },
        'ko': {
            'title': 'ISO Class 8 클린룸 에어백 모듈 정밀 조립',
            'subtitle': '부유 미립자 제어 양압 환기 시스템과 정전기 방지(ESD) 설비를 완비하여 최고 수준의 안전 부품을 조립합니다.',
            'badge_text': '클린룸 ISO 14644-1',
            'button_text': ''
        }
    },
    'facility_laboratories': {
        'en': {
            'title': 'Quality Control & Dynamic Testing Laboratories',
            'subtitle': 'Equipped with Mitutoyo 3D CMM inspection, thermal shock environmental chambers (-40°C to +120°C), and high-speed airbag deployment sensors.',
            'badge_text': 'Testing & Metrology Labs',
            'button_text': ''
        },
        'ja': {
            'title': '品質管理試験室および動的衝撃テストラボ',
            'subtitle': 'ミツトヨ製三次元測定機（CMM）、-40℃〜+120℃環境試験チャンバー、高速ミリ秒展開試験機を備えた総合検査施設。',
            'badge_text': '品質検査・測定ラボ',
            'button_text': ''
        },
        'zh': {
            'title': '质量控制实验室与动态冲击测试中心',
            'subtitle': '配备三丰（Mitutoyo）三坐标精密测量机（CMM）、-40°C至+120°C极端温湿试验箱及高速毫秒级气囊点火试验传感器。',
            'badge_text': '计量与测试实验室',
            'button_text': ''
        },
        'ko': {
            'title': '품질 관리 실험실 및 동적 충격 시험 연구소',
            'subtitle': '미쓰토요 3D CMM 3차원 측정기, -40°C~+120°C 극한 내환경 챔버 및 에어백 초고속 전개 시험 장비를 완비했습니다.',
            'badge_text': '시험 및 계측 연구소',
            'button_text': ''
        }
    },
    'facility_cta': {
        'en': {
            'title': 'Schedule a Facility Audit or Discuss Your OEM Project',
            'subtitle': 'Our Commercial & Engineering teams are ready to host technical audits or review custom component requirements.',
            'badge_text': 'COLLABORATE WITH US',
            'button_text': 'Submit B2B RFQ Inquiry'
        },
        'ja': {
            'title': '工場監査のご予約・新規OEMプロジェクトのご相談',
            'subtitle': '営業・技術エンジニアリングチームが、工場見学や技術仕様のレビューに迅速に対応いたします。',
            'badge_text': 'パートナーシップ',
            'button_text': 'B2B見積・相談を申し込む'
        },
        'zh': {
            'title': '预约工厂实地审核或商讨您的新主机厂项目',
            'subtitle': '我们的商务与工程团队随时恭候您的现场考察，并就定制零部件的技术规格与报价展开深入讨论。',
            'badge_text': '携手精诚合作',
            'button_text': '提交B2B采购意向'
        },
        'ko': {
            'title': '공장 방문 실사 예약 또는 OEM 프로젝트 상담',
            'subtitle': '당사의 영업 및 엔지니어링 팀은 기술 실사 방문을 환영하며 부품 사양 맞춤 상담을 제공할 준비가 되어 있습니다.',
            'badge_text': '비즈니스 협력',
            'button_text': 'B2B 견적 문의 제출'
        }
    },
    'quality_zero_defect': {
        'en': {
            'title': 'Uncompromising Quality Commitment for Driver Safety',
            'subtitle': 'As a global Tier-1 automotive supplier, Quality First and Zero Defect principles are ingrained in every phase from mold design to final audit.',
            'badge_text': 'ZERO DEFECT PHILOSOPHY',
            'button_text': ''
        },
        'ja': {
            'title': 'ドライバーの命を守る妥協なき品質への誓い',
            'subtitle': 'グローバルTier-1サプライヤーとして、「品質第一」と「ゼロディフェクト」の精神を金型設計から出荷検査に至る全工程で徹底。',
            'badge_text': 'ゼロディフェクト哲学',
            'button_text': ''
        },
        'zh': {
            'title': '守护驾乘者安全的零妥协品质承诺',
            'subtitle': '作为全球Tier-1汽车供应商，我们将“品质第一”与“零缺陷”理念深深植根于从模具开发到最终出厂审核的每一个环节。',
            'badge_text': '零缺陷质量哲学',
            'button_text': ''
        },
        'ko': {
            'title': '운전자의 생명을 지키는 타협 없는 품질에 대한 헌신',
            'subtitle': '글로벌 Tier-1 공급사로서 \'품질 제일\'과 \'제로 디펙트\' 원칙을 금형 설계부터 최종 출고 검사까지 전 과정에 철저히 적용합니다.',
            'badge_text': '무결점 품질 철학',
            'button_text': ''
        }
    },
    'quality_certifications_intro': {
        'en': {
            'title': 'Accredited Certifications & Quality Systems',
            'subtitle': 'Our plant operations and manufacturing lines undergo regular surveillance audits by accredited international registrars.',
            'badge_text': 'ACCREDITED CERTIFICATIONS',
            'button_text': ''
        },
        'ja': {
            'title': '国際認証および公式マネジメントシステム',
            'subtitle': 'すべての製造ラインと運用プロセスは、世界的な第三者認証機関による厳格な定期監査をクリアしています。',
            'badge_text': '取得済み国際認証',
            'button_text': ''
        },
        'zh': {
            'title': '权威第三方认证与全面质量管理体系',
            'subtitle': '我们的全线制造设施与工艺流程定期接受国际权威认证机构的严格复审与认证监督。',
            'badge_text': '权威资质认证',
            'button_text': ''
        },
        'ko': {
            'title': '공식 품질 경영 인증 및 규격 시스템',
            'subtitle': '당사의 모든 제조 라인과 운영 프로세스는 저명한 국제 독립 인증 기관으로부터 정기적인 심사를 통과하고 있습니다.',
            'badge_text': '공인 품질 인증',
            'button_text': ''
        }
    },
    'quality_regulatory_compliance': {
        'en': {
            'title': 'Indonesian Regulatory & Environmental Compliance',
            'subtitle': 'Strict adherence to Ministry of Industry (Kemenperin) guidelines, Ministry of Environment (KLHK) environmental standards, and Ministry of Manpower (Kemnaker) OHS laws.',
            'badge_text': 'INDONESIAN REGULATORY COMPLIANCE',
            'button_text': ''
        },
        'ja': {
            'title': 'インドネシア国内法令・環境規制の完全遵守',
            'subtitle': 'インドネシア工業省（Kemenperin）、環境林業省（KLHK）、労働省（Kemnaker）が定める諸法令を厳格に順守。',
            'badge_text': '現地法規制への適合',
            'button_text': ''
        },
        'zh': {
            'title': '印尼国家工业法规与生态环境保护法规合规',
            'subtitle': '严格执行印尼工业部（Kemenperin）产业规范、环保与林业部（KLHK）环境标准以及劳工部（Kemnaker）职业健康安全法规。',
            'badge_text': '印尼国家法规合规',
            'button_text': ''
        },
        'ko': {
            'title': '인도네시아 정부 규제 및 환경 법규 준수',
            'subtitle': '인도네시아 산업부(Kemenperin), 환경산림부(KLHK), 노동부(Kemnaker)의 안전보건 및 환경 관리 규정을 철저히 준수합니다.',
            'badge_text': '현지 법규 및 규제 준수',
            'button_text': ''
        }
    },
    'quality_flowchart': {
        'en': {
            'title': 'Integrated Quality Assurance Workflow',
            'subtitle': 'Phase 1: Raw Material Incoming Inspection, Phase 2: In-Process Quality Control, Phase 3: 3D CMM Geometric Verification, Phase 4: Pre-Delivery Final Audit.',
            'badge_text': 'QUALITY ASSURANCE WORKFLOW',
            'button_text': ''
        },
        'ja': {
            'title': '一貫した品質保証ワークフロー',
            'subtitle': '工程1：受入検査、工程2：インライン工程品質管理、工程3：3次元幾何公差測定、工程4：出荷前最終検査。',
            'badge_text': '品質保証プロセス',
            'button_text': ''
        },
        'zh': {
            'title': '全流程闭环质量控制与追溯流程',
            'subtitle': '阶段1：原材料来料检验，阶段2：制程实时质量把控，阶段3：3D三坐标尺寸验证，阶段4：出货前最终综合审核。',
            'badge_text': '全流程质量控制',
            'button_text': ''
        },
        'ko': {
            'title': '통합 품질 보증 프로세스',
            'subtitle': '1단계: 원자재 수입 검사, 2단계: 공정 중 실시간 품질 관리, 3단계: 3차원 CMM 기하 공차 측정, 4단계: 출하 전 최종 종합 감사.',
            'badge_text': '품질 보증 워크플로',
            'button_text': ''
        }
    },
    'quality_cta': {
        'en': {
            'title': 'Require Quality Compliance Sheets or ISO Certificates?',
            'subtitle': 'Contact our Quality Assurance division to request official copies of IATF 16949, ISO 14001, or RoHS/ELV declaration sheets.',
            'badge_text': 'GET CERTIFICATION COPIES',
            'button_text': 'Contact QA & Compliance Team'
        },
        'ja': {
            'title': '品質適合証明書やISO認証証書の写しが必要ですか？',
            'subtitle': 'IATF 16949、ISO 14001の認証登録証、RoHS/ELV適合宣言書のご請求は、品質保証部までご連絡ください。',
            'badge_text': '認証証書の請求',
            'button_text': '品質保証部へ問い合わせる'
        },
        'zh': {
            'title': '需要质量体系合规文件或ISO体系证书副本？',
            'subtitle': '欢迎联络我们的质量保证与法规部，获取IATF 16949、ISO 14001或RoHS/ELV有害物质禁用官方声明文件。',
            'badge_text': '索取认证资质文件',
            'button_text': '联系质量合规团队'
        },
        'ko': {
            'title': '품질 규정 준수 보고서 또는 ISO 인증서 사본이 필요하십니까?',
            'subtitle': 'IATF 16949, ISO 14001 등록 증명서 또는 RoHS/ELV 환경 규제 준수 확인서는 품질보증팀으로 문의해 주십시오.',
            'badge_text': '인증서 사본 요청',
            'button_text': '품질보증팀 문의하기'
        }
    },
    'products_catalog_intro': {
        'en': {
            'title': 'World-Class Automotive Component Catalog',
            'subtitle': 'Supplying integrated steering wheel systems, passenger airbag safety modules, and OEM-grade interior-exterior resin mouldings.',
            'badge_text': 'TIER-1 OEM PRODUCT PORTFOLIO',
            'button_text': ''
        },
        'ja': {
            'title': '世界水準の自動車部品製品カタログ',
            'subtitle': '高度な操舵フィーリングを実現するステアリングホイール、人命を守るエアバッグモジュール、高精度樹脂成形品を供給。',
            'badge_text': 'Tier-1 製品ポートフォリオ',
            'button_text': ''
        },
        'zh': {
            'title': '具备世界一流主机厂品质的汽车零部件产品目录',
            'subtitle': '供应高性能集成方向盘系统、乘员保护安全气囊模块及主机厂级高精度内外饰树脂注塑件。',
            'badge_text': 'Tier-1 核心产品目录',
            'button_text': ''
        },
        'ko': {
            'title': '세계적 수준의 자동차 부품 제품 카탈로그',
            'subtitle': '정밀 조향 스티어링 휠 시스템, 승객 보호 에어백 모듈 및 OEM 정품 규격 내외장 수지 성형 부품을 공급합니다.',
            'badge_text': 'Tier-1 제품 포트폴리오',
            'button_text': ''
        }
    },
    'products_technical_capabilities': {
        'en': {
            'title': 'Material Engineering & Micro-Tolerance Precision',
            'subtitle': 'Employing high-performance engineering resins (PP-TD20, ABS, PC/ABS, PA66-GF) engineered for vibration resistance and thermal stability.',
            'badge_text': 'MATERIAL ENGINEERING EXCELLENCE',
            'button_text': ''
        },
        'ja': {
            'title': '高度な材料工学とミクロン単位の精密成形技術',
            'subtitle': '優れた耐振動性、耐擦傷性、熱安定性を備えた高機能エンジニアリングプラスチック（PP-TD20、ABS、PC/ABS、PA66-GF）を採用。',
            'badge_text': '材料技術と超高精度',
            'button_text': ''
        },
        'zh': {
            'title': '高分子材料工程与微米级超精密制造公差',
            'subtitle': '采用高性能工程树脂材料（PP-TD20、ABS、PC/ABS、PA66-GF），具备优异的抗震动、耐刮擦及高低温热稳定性。',
            'badge_text': '材料工程与精密成型',
            'button_text': ''
        },
        'ko': {
            'title': '고분자 재료 공학과 마이크론 단위의 정밀 공차',
            'subtitle': '진동 흡수성, 내스크래치성 및 열안정성이 탁월한 고기능 엔지니어링 플라스틱(PP-TD20, ABS, PC/ABS, PA66-GF)을 사용합니다.',
            'badge_text': '재료 기술 및 정밀 가공',
            'button_text': ''
        }
    },
    'products_custom_oem': {
        'en': {
            'title': 'Tailored Component Solutions for Global OEMs',
            'subtitle': 'Comprehensive technical support from mold flow simulation and laser graining to rapid functional prototyping.',
            'badge_text': 'TAILORED OEM SOURCING',
            'button_text': ''
        },
        'ja': {
            'title': 'OEMメーカーの要求に応えるテーラーメイド開発ソリューション',
            'subtitle': '金型流動解析（モールドフロー）、微細レーザーシボ加工の選定、迅速なプロトタイプ試作まで一貫支援。',
            'badge_text': 'OEM個別カスタム開発',
            'button_text': ''
        },
        'zh': {
            'title': '专为主机厂（OEM）量身定制的工程解决方案',
            'subtitle': '提供从模流仿真分析、高精度激光皮纹选型到快速功能件打样的全流程技术支持服务。',
            'badge_text': '主机厂定制开发',
            'button_text': ''
        },
        'ko': {
            'title': '완성차 OEM 요구에 맞춘 부품 맞춤형 솔루션',
            'subtitle': '몰드 플로우 유동 해석, 레이저 텍스처 패터닝부터 신속한 기능성 프로토타입 시작품 제작까지 종합 지원합니다.',
            'badge_text': 'OEM 맞춤형 개발',
            'button_text': ''
        }
    },
    'products_cta': {
        'en': {
            'title': 'Interested in Co-Developing New Components with Us?',
            'subtitle': 'Submit a Request for Quotation (RFQ) and arrange a technical scoping session with our design engineering team.',
            'badge_text': 'B2B SOURCING INQUIRY',
            'button_text': 'Submit RFQ Inquiry'
        },
        'ja': {
            'title': '新規自動車部品の共同開発をお考えですか？',
            'subtitle': 'お見積り依頼（RFQ）の送付や、当社エンジニアとの技術検討ミーティングを随時受け付けております。',
            'badge_text': '新規開発・調達相談',
            'button_text': '見積・技術相談を申し込む'
        },
        'zh': {
            'title': '有意与我们联合开发新型汽车零部件？',
            'subtitle': '欢迎提交采购报价申请（RFQ），并与我们的研发工程团队预约深入的技术对接会议。',
            'badge_text': 'B2B采购咨询对接',
            'button_text': '提交RFQ报价咨询'
        },
        'ko': {
            'title': '새로운 자동차 부품을 공동 개발하고 싶으신가요?',
            'subtitle': '견적 요청(RFQ)을 제출하시고 당사 엔지니어링 팀과 기술 검토 세션을 예약해 보십시오.',
            'badge_text': 'B2B 개발 및 견적 문의',
            'button_text': 'RFQ 견적 문의 제출'
        }
    },
    'career_intro': {
        'en': {
            'title': 'Building Sustainable Careers in Precision Manufacturing',
            'subtitle': 'We believe exceptional products start with skilled, empowered people who champion craftsmanship and safety.',
            'badge_text': 'CAREER & TALENT DEVELOPMENT',
            'button_text': ''
        },
        'ja': {
            'title': '精密製造の現場で築く、持続可能で誇りあるキャリア',
            'subtitle': '卓越した製品づくりは、確かな技術と誠実さを持ち、安全文化を重んじる「人づくり」から始まります。',
            'badge_text': '人材育成とキャリア',
            'button_text': ''
        },
        'zh': {
            'title': '在现代精密制造业开创可持续职业生涯',
            'subtitle': '我们坚信，卓越的汽车零部件源于具备精湛工匠精神、职业操守并严格奉行安全第一的优秀人才。',
            'badge_text': '职业发展与人才培育',
            'button_text': ''
        },
        'ko': {
            'title': '정밀 제조 산업에서 구축하는 지속 가능한 전문 커리어',
            'subtitle': '당사는 뛰어난 제품이 장인정신과 안전 문화를 실천하는 유능한 인재로부터 탄생한다고 확신합니다.',
            'badge_text': '인재 육성 및 커리어',
            'button_text': ''
        }
    },
    'career_culture_benefits': {
        'en': {
            'title': 'Employee Wellbeing & Professional Growth',
            'subtitle': 'Direct Monozukuri training programs, full BPJS medical & labor coverage, hygienic cafeteria, and employee shuttle buses.',
            'badge_text': 'WORKPLACE CULTURE & PERKS',
            'button_text': ''
        },
        'ja': {
            'title': '充実した福利厚生と専門技術の向上環境',
            'subtitle': '日本品質のモノづくり教育、各種保険完備、清潔な社員食堂、通勤シャトルバスなど働きやすい環境を整備。',
            'badge_text': '働く環境と福利厚生',
            'button_text': ''
        },
        'zh': {
            'title': '员工关怀福祉与全方位职业晋升体系',
            'subtitle': '日本精益工艺实操培训、完善的国家医疗与劳动社会保险、卫生员工餐厅及定点员工通勤班车班次。',
            'badge_text': '企业文化与员工福利',
            'button_text': ''
        },
        'ko': {
            'title': '직원 복지 후생 및 전문 역량 개발 지원',
            'subtitle': '일본 본사 모노즈쿠리 기술 교육, 법정 4대 사회보험 완비, 위생적인 사내 식당 및 통근 셔틀버스를 제공합니다.',
            'badge_text': '근무 환경 및 복리후생',
            'button_text': ''
        }
    },
    'career_hiring_process': {
        'en': {
            'title': 'Transparent & Cost-Free Recruitment Stages',
            'subtitle': '1. Document Screening, 2. Technical Aptitude & Psychometric Test, 3. HR & User Interview, 4. Medical Checkup & Onboarding.',
            'badge_text': 'RECRUITMENT STAGES',
            'button_text': ''
        },
        'ja': {
            'title': '透明性高く費用一切不要の採用選考プロセス',
            'subtitle': '1. 書類選考、2. 専門技術適性・筆記試験、3. 面接（人事および配属先責任者）、4. 健康診断および入社手続き。',
            'badge_text': '選考ステップ',
            'button_text': ''
        },
        'zh': {
            'title': '阳光透明、全程免费的规范化招聘流程',
            'subtitle': '1. 简历资格初筛，2. 专业技术与综合能力测验，3. 人事与用人部门深度面试，4. 职业健康体检及入职培训。',
            'badge_text': '规范招聘环节',
            'button_text': ''
        },
        'ko': {
            'title': '투명하고 채용 수수료가 없는 채용 절차',
            'subtitle': '1단계: 서류 전형, 2단계: 직무 적성 및 기술 평가, 3단계: 인사 및 실무진 면접, 4단계: 신체검사 및 온보딩.',
            'badge_text': '채용 전형 단계',
            'button_text': ''
        }
    },
    'career_vacancies_intro': {
        'en': {
            'title': 'Open Opportunities & Job Vacancies',
            'subtitle': 'Explore current openings at our Cikampek Karawang plant and submit your application.',
            'badge_text': 'OPEN VACANCIES',
            'button_text': ''
        },
        'ja': {
            'title': '現在募集中のポジション一覧',
            'subtitle': 'チカンペック工場における最新の募集職種をご確認のうえ、ご応募ください。',
            'badge_text': '募集職種',
            'button_text': ''
        },
        'zh': {
            'title': '当前热招职位与职业机会',
            'subtitle': '欢迎查看芝坎佩工厂目前的开放招聘职位，期待您的加入。',
            'badge_text': '热招职位一览',
            'button_text': ''
        },
        'ko': {
            'title': '현재 채용 진행 중인 포지션',
            'subtitle': '치캄펙 카라왕 공장의 활성 채용 포지션을 확인하시고 지원서를 제출해 주십시오.',
            'badge_text': '채용 공고',
            'button_text': ''
        }
    },
    'contact_liaison_intro': {
        'en': {
            'title': 'Partner with PT Nihon Plast Indonesia',
            'subtitle': 'Ready to assist automotive OEMs with component sourcing, prototyping review, and factory audits.',
            'badge_text': 'COMMERCIAL & ENGINEERING LIAISON',
            'button_text': ''
        },
        'ja': {
            'title': 'PT Nihon Plast Indonesia とのパートナーシップ',
            'subtitle': '完成車メーカー（OEM）様向けの部品調達、技術試作の検討、工場監査のご要望に迅速に対応いたします。',
            'badge_text': '営業・技術窓口',
            'button_text': ''
        },
        'zh': {
            'title': '携手PT Nihon Plast Indonesia共筑辉煌',
            'subtitle': '竭诚为各大汽车主机厂提供零部件配套供应、技术原型试制及工厂质量审核服务。',
            'badge_text': '商务与技术联络窗口',
            'button_text': ''
        },
        'ko': {
            'title': 'PT 니혼 플라스트 인도네시아와 파트너십 구축',
            'subtitle': '완성차 OEM 고객사를 위한 부품 공급, 시제품 기술 검토 및 공장 품질 실사 지원을 완벽히 제공합니다.',
            'badge_text': '영업 및 기술 공식 창구',
            'button_text': ''
        }
    },
    'contact_info_card': {
        'en': {
            'title': 'Plant Location & Official Contacts',
            'subtitle': 'Indotaisei Industrial Estate Sector 1A Block P-1, Cikampek, Karawang 41373, West Java | Phone: +62 (264) 351-888 | Email: corporate@n-plast.co.id',
            'badge_text': 'HEADQUARTERS & MANUFACTURING PLANT',
            'button_text': ''
        },
        'ja': {
            'title': '工場所在地および公式連絡先',
            'subtitle': '西ジャワ州カラワン県チカンペック インドタイセイ工業団地 セクター1A ブロックP-1 | 電話: +62 (264) 351-888 | Email: corporate@n-plast.co.id',
            'badge_text': '本社および生産拠点',
            'button_text': ''
        },
        'zh': {
            'title': '工厂地理位置与官方联络渠道',
            'subtitle': '西爪哇省卡拉旺县芝坎佩印多泰成工业区1A区P-1地块 | 电话: +62 (264) 351-888 | 邮箱: corporate@n-plast.co.id',
            'badge_text': '总部与核心制造基地',
            'button_text': ''
        },
        'ko': {
            'title': '공장 위치 및 공식 연락처',
            'subtitle': '서부 자바주 카라왕 치캄펙 인도타이세이 산업단지 섹터 1A 블록 P-1 | 전화: +62 (264) 351-888 | 이메일: corporate@n-plast.co.id',
            'badge_text': '본사 및 제조 공장',
            'button_text': ''
        }
    },
    'contact_download_deck': {
        'en': {
            'title': 'Company Profile & Capability Deck',
            'subtitle': 'Download our corporate deck summarizing injection presses, plant capacity, and IATF 16949 / ISO 14001 certifications.',
            'badge_text': 'CORPORATE BROCHURE',
            'button_text': 'Download Company Profile (PDF)'
        },
        'ja': {
            'title': '会社案内・事業概要パンフレット（PDF）',
            'subtitle': '射出成形機の設備能力、生産キャパシティ、品質認証規格（IATF 16949 / ISO 14001）をまとめた公式資料をダウンロードいただけます。',
            'badge_text': '会社案内資料',
            'button_text': '会社案内をダウンロード（PDF）'
        },
        'zh': {
            'title': '企业简介与制造能力手册（PDF）',
            'subtitle': '点击下载包含大型伺服注塑机台数据、制造产能规格及IATF 16949/ISO 14001资质证书的官方企业画册。',
            'badge_text': '企业官方画册',
            'button_text': '下载企业简介手册（PDF）'
        },
        'ko': {
            'title': '회사 소개서 및 설비 역량 브로슈어(PDF)',
            'subtitle': '보유 사출 성형기 제원, 공장 생산 능력 및 IATF 16949 / ISO 14001 인증 요약서를 다운로드하실 수 있습니다.',
            'badge_text': '공식 브로슈어',
            'button_text': '회사 소개서 다운로드(PDF)'
        }
    },
    'contact_rfq_form_intro': {
        'en': {
            'title': 'Request for Quotation & Technical Inquiry Form',
            'subtitle': 'Please complete the form below. Our Commercial & Engineering teams will respond within 1 business day.',
            'badge_text': 'B2B RFQ SUBMISSION FORM',
            'button_text': ''
        },
        'ja': {
            'title': 'B2B見積依頼・技術相談フォーム',
            'subtitle': '以下のフォームに必要事項をご記入ください。営業・技術部門より1営業日以内にご回答いたします。',
            'badge_text': '見積・相談フォーム',
            'button_text': ''
        },
        'zh': {
            'title': '采购报价意向（RFQ）与技术咨询表格',
            'subtitle': '请填写以下表格。我们的技术与商务支持团队将在一个工作日内给您正式回复。',
            'badge_text': 'B2B RFQ在线申请表',
            'button_text': ''
        },
        'ko': {
            'title': 'B2B 견적 의뢰 및 기술 상담 양식',
            'subtitle': '아래 양식을 작성해 주시면 당사의 기술 및 영업팀이 영업일 기준 1일 이내에 신속히 답변해 드립니다.',
            'badge_text': 'B2B RFQ 신청 양식',
            'button_text': ''
        }
    },
    'contact_map_section': {
        'en': {
            'title': 'Indotaisei Industrial Estate Cikampek Location Map',
            'subtitle': 'Conveniently situated off Jakarta-Cikampek Toll Road KM 68 (Kalihurip Utama Interchange).',
            'badge_text': 'PLANT NAVIGATION MAP',
            'button_text': ''
        },
        'ja': {
            'title': 'インドタイセイ工業団地（チカンペック）アクセスマップ',
            'subtitle': 'ジャカルタ〜チカンペック高速道路 KM 68 カリフリップ・ウタマ料金所より直結。',
            'badge_text': '交通アクセス',
            'button_text': ''
        },
        'zh': {
            'title': '芝坎佩印多泰成工业区交通区位地图',
            'subtitle': '紧邻雅加达-芝坎佩高速公路68公里处（Kalihurip Utama主收费站出口），交通便利。',
            'badge_text': '厂区交通导航图',
            'button_text': ''
        },
        'ko': {
            'title': '인도타이세이 치캄펙 산업단지 오시는 길 지도',
            'subtitle': '자카르타-치캄펙 고속도로 KM 68(칼리후립 우타마 톨게이트)에 위치하여 접근성이 매우 뛰어납니다.',
            'badge_text': '공장 위치 지도',
            'button_text': ''
        }
    },
    'gallery_header': {
        'en': {
            'title': 'Plant Activities & Facility Photo Gallery',
            'subtitle': 'High-resolution photographic documentation of automotive precision manufacturing, 5S safety culture, and plant facilities.',
            'badge_text': 'MANUFACTURING PHOTO DOCUMENTATION',
            'button_text': ''
        },
        'ja': {
            'title': 'フォトギャラリー：生産現場と工場設備',
            'subtitle': '自動車安全部品の製造、5S安全衛生活動、最先端成形設備の高解像度フォト記録。',
            'badge_text': '製造現場写真アーカイブ',
            'button_text': ''
        },
        'zh': {
            'title': '工厂车间生产纪实与先进设施画册',
            'subtitle': '记录汽车零部件注塑生产、5S安全管理文化及现代化工厂设施的高清实景照片。',
            'badge_text': '生产制造影集',
            'button_text': ''
        },
        'ko': {
            'title': '공장 활동 및 생산 시설 사진 갤러리',
            'subtitle': '자동차 정밀 부품 제조, 5S 안전보건 활동 및 첨단 공장 설비 현장을 담은 고해상도 기록입니다.',
            'badge_text': '제조 현장 사진 갤러리',
            'button_text': ''
        }
    },
    'news_header': {
        'en': {
            'title': 'Corporate News & Industry Updates',
            'subtitle': 'Explore recent updates on plant expansions, quality awards, and community engagements from PT Nihon Plast Indonesia.',
            'badge_text': 'LATEST UPDATES',
            'button_text': ''
        },
        'ja': {
            'title': '企業ニュース・最新トピックス',
            'subtitle': '工場の最新動向、品質表彰、地域社会貢献活動などのニュースをお届けします。',
            'badge_text': '最新情報',
            'button_text': ''
        },
        'zh': {
            'title': '企业官方新闻与行业动态资讯',
            'subtitle': '敬请关注PT Nihon Plast Indonesia在工厂扩产、质量荣誉及社会公益方面的最新动态。',
            'badge_text': '新闻与最新动态',
            'button_text': ''
        },
        'ko': {
            'title': '기업 뉴스 및 산업 동향 소식',
            'subtitle': '공장 시설 확장, 품질 수상 내역 및 사회공헌 활동에 관한 당사의 최신 뉴스를 확인하십시오.',
            'badge_text': '최신 뉴스',
            'button_text': ''
        }
    }
}

print("Loaded section translations mapping for all 44 sections.")
