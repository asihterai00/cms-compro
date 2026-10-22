# -*- coding: utf-8 -*-
import json
import generate_sections_seed

secs = generate_sections_seed.secs
items = generate_sections_seed.items
sec_trans = generate_sections_seed.sec_trans

print(f"Total sections: {len(secs)}, Total items: {len(items)}")

# Build item translations dictionary by ID or item_key
# id -> {en: {title, subtitle, content}, ja: {...}, zh: {...}, ko: {...}}
item_trans = {}

# 1. Stats (ID 1 - 4)
item_trans[1] = {
    'en': {'title': 'IATF 16949 / ISO 14001', 'subtitle': 'Global Automotive Quality Standards', 'content': ''},
    'ja': {'title': 'IATF 16949 / ISO 14001', 'subtitle': '国際自動車産業品質規格認証', 'content': ''},
    'zh': {'title': 'IATF 16949 / ISO 14001', 'subtitle': '全球汽车工业质量管理体系', 'content': ''},
    'ko': {'title': 'IATF 16949 / ISO 14001', 'subtitle': '글로벌 자동차 산업 품질 표준', 'content': ''}
}
item_trans[2] = {
    'en': {'title': '4 Core Product Lines', 'subtitle': 'Steering, Airbag, Interior & Exterior Resins', 'content': ''},
    'ja': {'title': '4大主要製品ライン', 'subtitle': 'ステアリング、エアバッグ、内外装樹脂部品', 'content': ''},
    'zh': {'title': '四大核心汽车产品线', 'subtitle': '方向盘、安全气囊、内外饰树脂组件', 'content': ''},
    'ko': {'title': '4대 핵심 제품군', 'subtitle': '스티어링, 에어백, 내외장 수지 부품', 'content': ''}
}
item_trans[3] = {
    'en': {'title': 'Cikampek Karawang Plant', 'subtitle': 'Class 8 Cleanroom & 150T-2500T Presses', 'content': ''},
    'ja': {'title': 'チカンペック・カラワン工場', 'subtitle': 'クラス8クリーンルーム・150T〜2500T成形機', 'content': ''},
    'zh': {'title': '西爪哇芝坎佩工业园工厂', 'subtitle': 'Class 8无尘洁净室与150T-2500T机群', 'content': ''},
    'ko': {'title': '치캄펙 카라왕 제조 공장', 'subtitle': 'Class 8 클린룸 및 150T-2500T 사출기', 'content': ''}
}
item_trans[4] = {
    'en': {'title': 'Zero Defect & Zero LTI', 'subtitle': 'Indonesian Kemnaker K3 & 5S Culture', 'content': ''},
    'ja': {'title': 'ゼロディフェクト・無災害労働', 'subtitle': '徹底した5S文化および安全衛生法準拠', 'content': ''},
    'zh': {'title': '零缺陷与零工伤事故', 'subtitle': '严格执行5S文化与印尼安全卫生标准', 'content': ''},
    'ko': {'title': '무결점 및 무재해 달성', 'subtitle': '인도네시아 K3 노동 안전 규정 및 5S 문화', 'content': ''}
}

# 2. Hero Slides (ID 78 - 81)
item_trans[78] = {
    'en': {
        'title': 'Japanese Monozukuri Precision & High-Tonnage Injection Molding',
        'subtitle': 'GLOBAL TIER-1 AUTOMOTIVE SAFETY & INTERIOR SYSTEMS',
        'content': 'Trusted manufacturing partner to world OEMs with 22 servo injection presses (150T–2,500T), ISO Class 8 cleanroom, and IATF 16949 accreditation.'
    },
    'ja': {
        'title': '日本のモノづくり技術と大型高精度射出成形システム',
        'subtitle': 'グローバル Tier-1 自動車安全・内装部品システム',
        'content': '150Tから2,500Tまで対応する22台のサーボ射出成形機、クラス8クリーンルーム、IATF 16949認証を備えた信頼の製造パートナー。'
    },
    'zh': {
        'title': '日本精密工匠制造精神与高吨位汽车注塑成型技术',
        'subtitle': '全球 Tier-1 汽车安全与内饰系统',
        'content': '拥有22台150T至2500T伺服注塑机集群、Class 8无尘车间及IATF 16949认证，为主机厂提供可信赖的配套。'
    },
    'ko': {
        'title': '일본 모노즈쿠리 정밀 기술과 대형 고정밀 사출 성형 시스템',
        'subtitle': '글로벌 Tier-1 자동차 안전 및 내장재 시스템',
        'content': '150T~2500T 서보 사출기 22대, Class 8 클린룸 및 IATF 16949 인증을 갖춘 글로벌 OEM 공식 제조 파트너.'
    }
}
item_trans[79] = {
    'en': {
        'title': 'Critical Airbag Safety Modules with 30ms High-Speed Deployment',
        'subtitle': 'UNCOMPROMISING LIFE-SAVING OCCUPANT PROTECTION',
        'content': 'Assembled in an ISO 14644-1 Class 8 cleanroom with rigorous electrostatic discharge (ESD) controls and 100% inflator barcode traceability.'
    },
    'ja': {
        'title': '30ミリ秒の超高速展開を誇る最重要エアバッグモジュール',
        'subtitle': '乗員保護のための妥協なき人命救命システム',
        'content': '静電気対策（ESD）を徹底したISO 14644-1 クラス8クリーンルームで組み立てられ、インフレーターの100%バーコード追跡を管理。'
    },
    'zh': {
        'title': '30毫秒超高速展开的高可靠性乘员安全气囊模块',
        'subtitle': '守护生命的零妥协乘员保护系统',
        'content': '在ISO 14644-1 Class 8级正压无尘车间严格装配，具备全流程防静电（ESD）控制与气囊发生器100%条码溯源。'
    },
    'ko': {
        'title': '30밀리초 초고속 전개를 자랑하는 생명 구조 에어백 모듈',
        'subtitle': '타협 없는 탑승자 보호 안전 시스템',
        'content': '정전기 방지(ESD) 제어 ISO 14644-1 Class 8 클린룸에서 엄격히 조립되며 인플레이터 부품 100% 바코드 추적 관리를 적용합니다.'
    }
}
item_trans[80] = {
    'en': {
        'title': 'Takumi Master Craftsmanship: Genuine Leather Steering Wheels',
        'subtitle': 'PREMIUM CABIN ERGONOMICS & DIE-CAST RIGIDITY',
        'content': 'Lightweight magnesium/aluminum die-cast core engineered with high-density polyurethane foam and precision hand-stitched premium leather.'
    },
    'ja': {
        'title': '匠の技が息づく最高級本革巻きステアリングホイール',
        'subtitle': '極上の操舵フィーリングと高剛性ダイカスト芯金',
        'content': '軽量高剛性マグネシウム・アルミ芯金と高密度ウレタン、職人の手縫いによるプレミアム本革巻きがもたらす極上の質感。'
    },
    'zh': {
        'title': '匠人手工缝制工艺：豪华真皮汽车方向盘安全总成',
        'subtitle': '卓越人机工程触感与高刚性压铸骨架',
        'content': '轻量化高刚性镁铝合金压铸芯骨架，结合高密度聚氨酯发泡与工匠大师手工缝制的原装真皮包裹。'
    },
    'ko': {
        'title': '타쿠미 장인정신이 깃든 최고급 천연 가죽 스티어링 휠',
        'subtitle': '프리미엄 콕핏 인체공학 및 고강성 다이캐스팅',
        'content': '경량 고강성 마그네슘/알루미늄 다이캐스팅 코어에 고밀도 폴리우레탄 폼과 정밀 수작업 천연 가죽 래핑을 결합했습니다.'
    }
}
item_trans[81] = {
    'en': {
        'title': 'Green Monozukuri & Carbon Neutrality ESG Initiatives',
        'subtitle': 'SUSTAINABLE ECO-RESIN & SOLAR PV INTEGRATION',
        'content': 'Eliminating 450 tons of CO₂ emissions annually via rooftop photovoltaic solar arrays and adopting eco-friendly recycled polypropylene resins.'
    },
    'ja': {
        'title': '環境共生型モノづくりとカーボンニュートラル推進（ESG）',
        'subtitle': '再生エコ樹脂の採用と屋上太陽光発電（ソーラーPV）',
        'content': '屋上ソーラーパネル設置により年間450トンのCO₂を削減し、環境負荷の少ないリサイクル樹脂の積極活用を推進。'
    },
    'zh': {
        'title': '绿色低碳制造与碳中和可持续发展规划（ESG）',
        'subtitle': '环保循环再造树脂与屋顶太阳能光伏深度应用',
        'content': '通过安装屋顶光伏发电系统每年减少450吨二氧化碳排放，并广泛采用环保低碳聚丙烯再生树脂。'
    },
    'ko': {
        'title': '그린 모노즈쿠리 및 탄소 중립 친환경 ESG 경영',
        'subtitle': '지속 가능한 재생 친환경 수지 및 루프탑 태양광 연계',
        'content': '공장 루프탑 태양광 발전을 통해 연간 450톤의 탄소 배출을 감축하고 친환경 재생 폴리프로필렌 수지를 적용합니다.'
    }
}

# 3. Facility Preview Cards (ID 82, 83, 84)
item_trans[82] = {
    'en': {
        'title': 'Engineering & Mold R&D',
        'subtitle': 'Micro-Tolerance Moulding',
        'content': 'In-house engineering team supporting precision mould manufacturing and micron-tolerance maintenance.'
    },
    'ja': {
        'title': '金型開発・エンジニアリングR&D',
        'subtitle': 'ミクロン単位の精密金型技術',
        'content': 'ミクロン単位の樹脂成形公差を保証する社内技術チームによる金型開発・保全サポート。'
    },
    'zh': {
        'title': '工程模具研发与精密维护中心',
        'subtitle': '微米级高精密模具制造',
        'content': '专业工程技术团队全力支持微米级高精度汽车注塑模具的设计制造与日常预防性维护。'
    },
    'ko': {
        'title': '엔지니어링 및 금형 R&D 센터',
        'subtitle': '마이크론 정밀 금형 기술',
        'content': '마이크론 단위 정밀 수지 사출을 위한 전담 엔지니어링 팀의 금형 제작 및 유지보수 지원.'
    }
}
item_trans[83] = {
    'en': {
        'title': 'High-Tonnage Injection Line',
        'subtitle': '150T - 2,500T Fleet',
        'content': 'Fleet of 22 multi-tonnage injection presses capable of forming large interior and exterior automotive panels.'
    },
    'ja': {
        'title': '大型サーボ高圧射出成形ライン',
        'subtitle': '150トン〜2,500トンの成形機群',
        'content': '大型インパネや外装トリム部品を効率的に成形する22台の大型・中型射出成形機群。'
    },
    'zh': {
        'title': '大型高吨位伺服精密注塑生产线',
        'subtitle': '150T至2500T全系列机群',
        'content': '配备22台多吨位注塑机，具备高效率生产大型汽车内饰仪表板及外观装饰件的核心能力。'
    },
    'ko': {
        'title': '대형 톤수 정밀 사출 라인',
        'subtitle': '150T~2,500T 다톤수 머신 라인업',
        'content': '대형 내장재 인스트루먼트 패널 및 외장 트림을 안정적으로 생산하는 22대의 서보 사출 성형기 라인.'
    }
}
item_trans[84] = {
    'en': {
        'title': 'Safety Module Cleanroom',
        'subtitle': 'ISO Class 8 Assembly',
        'content': 'Sterile assembly environment certified to ISO Class 8 for airbag occupant safety modules and steering switches.'
    },
    'ja': {
        'title': '安全部品専用クリーンルーム組立棟',
        'subtitle': 'ISOクラス8清浄度管理',
        'content': 'エアバッグモジュールやステアリングスイッチの精密アセンブリを行うISOクラス8の無塵環境。'
    },
    'zh': {
        'title': '生命安全模块净化无尘装配车间',
        'subtitle': 'ISO Class 8洁净度控制',
        'content': '符合ISO Class 8级标准的无尘装配环境，专门用于高可靠性安全气囊模块及方向盘集成控制键装配。'
    },
    'ko': {
        'title': '에어백 안전 모듈 클린룸 조립 시설',
        'subtitle': 'ISO Class 8 청정도 환경',
        'content': '에어백 안전 모듈 및 스티어링 휠 통합 스위치 정밀 조립을 위한 ISO Class 8 무진 클린룸 환경.'
    }
}

# 4. Vehicle Explorer (ID 13 - 16)
item_trans[13] = {
    'en': {'title': 'Steering Wheel Systems & Ergonomics', 'subtitle': 'Zone: Driver Cockpit Interface', 'content': 'Lightweight die-cast core with integral polyurethane foam, handcrafted premium leather, and multifunction controls.'},
    'ja': {'title': 'ステアリングホイール（操舵システム）', 'subtitle': 'ゾーン：コックピットインターフェース', 'content': '軽量ダイカスト芯金、高密度インテグラルウレタン、職人の手縫い本革、多機能スイッチを一体化。'},
    'zh': {'title': '多功能方向盘系统与人机工学组件', 'subtitle': '区域：驾驶舱人机交互核心', 'content': '高强度轻量化压铸合金骨架，集成高密度发泡、手工顶级真皮包裹及多功能巡航音响控制按键。'},
    'ko': {'title': '스티어링 휠 시스템 및 운전석 인체공학', 'subtitle': '구역: 운전자 조향 인터페이스', 'content': '경량 다이캐스팅 코어에 고밀도 폴리우레탄 폼, 장인 수작업 가죽 및 다기능 스위치 제어 시스템을 통합.'}
}
item_trans[14] = {
    'en': {'title': 'Airbag Safety Modules & Inflator Encasement', 'subtitle': 'Zone: Active & Passive Safety', 'content': 'High-speed occupant protection system engineered to deploy in 20 to 30 milliseconds for absolute safety.'},
    'ja': {'title': 'エアバッグモジュール（乗員保護システム）', 'subtitle': 'ゾーン：安全保護システム', 'content': 'ミリ秒単位（20〜30ms）で確実に展開し、ドライバーと乗員の命を確実に保護する安全設計。'},
    'zh': {'title': '安全气囊模块与充气发生器安全防护总成', 'subtitle': '区域：主动与被动乘员保护', 'content': '高速乘员保护系统，专为在碰撞瞬间20至30毫秒内毫厘不差完全展开而精密设计。'},
    'ko': {'title': '에어백 안전 모듈 및 인플레이터 하우징', 'subtitle': '구역: 액티브 및 패시브 안전 시스템', 'content': '충돌 발생 시 20~30밀리초 이내에 완벽히 전개되도록 설계된 초고속 승객 보호 시스템.'}
}
item_trans[15] = {
    'en': {'title': 'Instrument Panel & Interior Resins', 'subtitle': 'Zone: Cabin Luxury & Ergonomics', 'content': 'Dashboard components, garnish trims, AC louvers, and center consoles engineered for thermal resistance and low VOC emissions.'},
    'ja': {'title': '内装樹脂・センターコンソール部品', 'subtitle': 'ゾーン：キャビン内装・快適空間', 'content': 'インパネガーニッシュ、超精密エアコンルーバー、センターコンソールなど耐熱・耐光性に優れた高品位樹脂。'},
    'zh': {'title': '仪表板总成及中控高品质内饰件', 'subtitle': '区域：豪华座舱人机工程', 'content': '仪表板骨架、装饰面板、精密空调出风口叶片及中央控制台，具备高耐温性与超低VOC环保指标。'},
    'ko': {'title': '인스트루먼트 패널 및 고급 인테리어 수지 부품', 'subtitle': '구역: 캐빈 인테리어 및 편의 사양', 'content': '대시보드 패널, 가니시 트림, 에어컨 루버 및 센터 콘솔 등 내열성 및 저VOC 친환경 수지 부품.'}
}
item_trans[16] = {
    'en': {'title': 'Cleanroom Class 8 Assembly Area', 'subtitle': 'Zone: High Precision Cleanliness', 'content': 'Dust-free and ESD-protected manufacturing cleanroom for critical sensor and airbag module integration.'},
    'ja': {'title': 'クリーンルーム組立エリア（クラス8）', 'subtitle': 'ゾーン：高精度清浄環境', 'content': '重要センサーや火工品（インフレーター）の精密アセンブリを行うため、静電気防止と防塵陽圧管理を徹底。'},
    'zh': {'title': 'Class 8级无尘净化车间装配中心', 'subtitle': '区域：高精度超净生产空间', 'content': '全封闭防尘与防静电（ESD）控制环境，专用于高精度传感器和气囊模块的零差错集成装配。'},
    'ko': {'title': 'Cleanroom Class 8 정밀 조립 전용 구역', 'subtitle': '구역: 고청정 무진 환경', 'content': '핵심 센서 및 에어백 모듈의 정밀 결합을 위한 방진 및 정전기 방지(ESD) 청정 생산 환경.'}
}

# 5. Quality Pillars (ID 17 - 20)
item_trans[17] = {
    'en': {'title': 'Raw Material QA', 'subtitle': '01', 'content': 'Spectrometric inspection and density analysis of raw resins, genuine leathers, and PUR foam.'},
    'ja': {'title': '原材料受入検査', 'subtitle': '01', 'content': '受入原材料の分光分析、樹脂ペレット密度試験、本革・ウレタン素材の厳格な品質検査。'},
    'zh': {'title': '严密原材料来料质检', 'subtitle': '01', 'content': '原材料光谱分析、高分子塑料粒子密度测试、真皮与聚氨酯发泡材料严密质检。'},
    'ko': {'title': '원자재 품질 보증 수입 검사', 'subtitle': '01', 'content': '원재료 분광 분석, 수지 펠릿 밀도 테스트, 천연 가죽 및 우레탄 폼 정밀 검사.'}
}
item_trans[18] = {
    'en': {'title': 'Precision Injection', 'subtitle': '02', 'content': 'Japanese servo injection machinery with micro-temperature and pressure control for micron tolerances.'},
    'ja': {'title': '高精度射出成形技術', 'subtitle': '02', 'content': '金型温度・保圧をミクロン単位でリアルタイム制御する最新サーボ射出成形技術。'},
    'zh': {'title': '超精密注塑工艺控制', 'subtitle': '02', 'content': '日本伺服注塑成型技术，具备精密模温与保压微控系统，确保微米级制造公差。'},
    'ko': {'title': '고정밀 서보 사출 성형 공정', 'subtitle': '02', 'content': '금형 온도 및 보압을 정밀 제어하는 일본 서보 사출 성형 기술로 마이크론 공차 실현.'}
}
item_trans[19] = {
    'en': {'title': 'Master Craftsmanship & CMM', 'subtitle': '03', 'content': 'Mitutoyo laser-calibrated 3D Coordinate Measuring Machine (CMM) geometric inspection.'},
    'ja': {'title': '匠の技と3次元CMM検査', 'subtitle': '03', 'content': 'ミツトヨ製レーザー校正三次元測定機（CMM）による高精度幾何公差検証。'},
    'zh': {'title': '工匠技艺与三坐标几何全检', 'subtitle': '03', 'content': '三丰（Mitutoyo）激光校准3D三坐标精密几何尺寸公差全检与工艺校核。'},
    'ko': {'title': '장인정신 및 3차원 CMM 정밀 검사', 'subtitle': '03', 'content': '미쓰토요 레이저 교정 3D CMM 3차원 측정기를 통한 정밀 기하학적 공차 검증.'}
}
item_trans[20] = {
    'en': {'title': 'Safety Deployment Lab', 'subtitle': '04', 'content': 'High-speed crash testing and drop-tower sensor impact certification for total safety.'},
    'ja': {'title': '展開衝撃試験ラボ', 'subtitle': '04', 'content': 'ミリ秒単位の高速展開試験およびドロップタワー衝撃試験による絶対的な安全性認証。'},
    'zh': {'title': '动态安全点火试验中心', 'subtitle': '04', 'content': '毫秒级高速点火展开碰撞试验与落锤冲击试验，确保人命攸关安全指标绝对达标。'},
    'ko': {'title': '초고속 전개 안전 연구소', 'subtitle': '04', 'content': '밀리초 단위 초고속 전개 시험 및 낙하 충격 시험을 통한 완벽한 안전성 검증.'}
}

# 6. About Values (ID 21 - 24)
item_trans[21] = {
    'en': {'title': 'Quality First (Zero Defect)', 'subtitle': 'Core Value 01', 'content': 'Zero-defect philosophy ingrained into every stage from product engineering to mass delivery.'},
    'ja': {'title': '品質第一（ゼロディフェクト）', 'subtitle': '核心理念 01', 'content': '製品設計から量産出荷に至るすべての段階でゼロディフェクト哲学を徹底実践。'},
    'zh': {'title': '品质第一（零缺陷原则）', 'subtitle': '核心价值观 01', 'content': '从产品开发设计到批量交付的各个阶段，全员践行零缺陷品质第一哲学。'},
    'ko': {'title': '품질 제일주의 (무결점 원칙)', 'subtitle': '핵심 가치 01', 'content': '제품 설계부터 양산 납품에 이르기까지 전 단계에서 무결점 품질 제일 철학 실천.'}
}
item_trans[22] = {
    'en': {'title': 'Customer Trust', 'subtitle': 'Core Value 02', 'content': 'Earning enduring partnerships through precision delivery, transparency, and technical agility.'},
    'ja': {'title': '顧客信頼の構築', 'subtitle': '核心理念 02', 'content': '納期遵守、透明性、高度な技術対応力により、国内外完成車メーカーとの強固な信頼を構築。'},
    'zh': {'title': '深受客户信赖', 'subtitle': '核心价值观 02', 'content': '以精准准时交付、透明沟通及敏捷技术响应，赢得各大汽车主机厂的长期信赖。'},
    'ko': {'title': '고객 신뢰 최우선', 'subtitle': '핵심 가치 02', 'content': '정확한 납기 준수, 투명한 운영 및 민첩한 기술 지원을 통해 고객 신뢰 구축.'}
}
item_trans[23] = {
    'en': {'title': 'Monozukuri Spirit', 'subtitle': 'Core Value 03', 'content': 'Respecting craftsmanship, kaizen daily improvements, and deep pride in manufacturing excellence.'},
    'ja': {'title': 'モノづくり精神とカイゼン', 'subtitle': '核心理念 03', 'content': 'クラフトマンシップ、日々のカイゼン活動、そして世界に誇るモノづくりへの情熱と誇り。'},
    'zh': {'title': '精益造物工匠精神（Monozukuri）', 'subtitle': '核心价值观 03', 'content': '尊崇工匠精神、日日践行持续改善（Kaizen），对精益制造卓越追求怀抱自豪。'},
    'ko': {'title': '모노즈쿠리 장인정신', 'subtitle': '핵심 가치 03', 'content': '장인정신에 대한 존중, 일상적인 카이젠 개선 활동 및 모노즈쿠리 제조에 대한 자부심.'}
}
item_trans[24] = {
    'en': {'title': 'Harmony & Workplace Safety', 'subtitle': 'Core Value 04', 'content': 'Fostering accident-free workplaces, employee dignity, and harmonious environmental stewardship.'},
    'ja': {'title': '調和と安全文化', 'subtitle': '核心理念 04', 'content': '無災害労働環境の徹底、従業員の尊厳、そして地域社会・自然環境との調和共生。'},
    'zh': {'title': '和谐共生与安全文化', 'subtitle': '核心价值观 04', 'content': '全力构建零工伤安全生产环境，维护员工尊严，促进人与自然环境和谐共生。'},
    'ko': {'title': '조화와 안전 최우선 문화', 'subtitle': '핵심 가치 04', 'content': '무재해 안전 작업장 조성, 직원의 존엄성 존중 및 자연환경과의 조화로운 공존.'}
}

# 7. Quality Flowchart (ID 39 - 42)
item_trans[39] = {
    'en': {'title': 'Quality Assurance (QA)', 'subtitle': 'APQP, FMEA, PPAP, MSA', 'content': 'Proactive quality planning adopting APQP, FMEA, PPAP, and MSA methodologies to prevent defect risks from the mold design phase.'},
    'ja': {'title': '品質保証（QA）体制', 'subtitle': 'APQP, FMEA, PPAP, MSA', 'content': '金型設計段階から潜在不具合を防止するため、APQP、FMEA、PPAP、MSA手法を徹底導入した品質先行管理。'},
    'zh': {'title': '全面质量先期策划（QA）', 'subtitle': 'APQP, FMEA, PPAP, MSA', 'content': '在模具开发初期全面导入APQP、FMEA、PPAP和MSA工具，实现从源头杜绝缺陷隐患。'},
    'ko': {'title': '품질 보증(QA) 사전 기획', 'subtitle': 'APQP, FMEA, PPAP, MSA', 'content': '금형 설계 단계부터 잠재적 결함을 사전에 방지하기 위해 APQP, FMEA, PPAP 및 MSA 기법을 전면 적용.'}
}
item_trans[40] = {
    'en': {'title': 'Quality Control (QC)', 'subtitle': '100% In-Line Inspection', 'content': 'Automated high-resolution camera vision systems and sensory manual inspections at every critical process.'},
    'ja': {'title': '製造工程内品質管理（QC）', 'subtitle': '100%全数インライン検査', 'content': '高解像度画像認識カメラによる自動判定と、熟練検査員による官能検査を重要工程すべてで実施。'},
    'zh': {'title': '制程制造质量控制（QC）', 'subtitle': '100%全流程在线品控', 'content': '在所有核心生产工序配备高像素AI工业视觉检测系统与经验丰富的检验员感官全检。'},
    'ko': {'title': '제조 공정 품질 관리(QC)', 'subtitle': '100% 전수 인라인 검사', 'content': '모든 핵심 공정에서 고해상도 비전 카메라 자동 판독 시스템과 숙련된 감각 검사를 병행.'}
}
item_trans[41] = {
    'en': {'title': 'Quality Engineering (QE)', 'subtitle': 'Metrology & Lab Verification', 'content': 'Micron-level dimensional verification with Mitutoyo 3D CMM, thermal cycling, and drop-tower impact testing.'},
    'ja': {'title': '品質技術（QE）計測検証', 'subtitle': '精密測定・ラボ試験', 'content': 'ミツトヨ製3D CMMによるミクロン寸法検証、熱サイクル環境試験、落下衝撃試験の実施。'},
    'zh': {'title': '质量工程与实验室验证（QE）', 'subtitle': '高精测量与实车模拟', 'content': '通过三丰3D三坐标进行微米级尺寸全方位复核，并执行高低温冷热冲击及落锤碰撞测试。'},
    'ko': {'title': '품질 엔지니어링 및 실험실 검증(QE)', 'subtitle': '정밀 계측 및 내환경 시험', 'content': '미쓰토요 3D CMM 마이크론 치수 검증, 열충격 환경 시험 및 낙하 충격 시뮬레이션 수행.'}
}
item_trans[42] = {
    'en': {'title': 'Kaizen & Continuous Improvement', 'subtitle': 'PDCA & 5S Culture', 'content': 'Company-wide Quality Control Circles (QCC) driving relentless daily improvements across all production tiers.'},
    'ja': {'title': 'カイゼンと継続的改善', 'subtitle': 'PDCAサイクルと5S文化', 'content': '全従業員が参加する小集団改善活動（QCC）とPDCAの実践により、品質と生産性を日々向上。'},
    'zh': {'title': '持续改善与精益改善（Kaizen）', 'subtitle': 'PDCA循环与5S文化', 'content': '全员积极推行品管圈（QCC）改善活动与PDCA闭环管理，实现品质与效率的每日精进。'},
    'ko': {'title': '카이젠 및 지속적 개선 활동', 'subtitle': 'PDCA 및 5S 문화', 'content': '전 임직원이 참여하는 품질 분임조(QCC) 활동과 끊임없는 현장 개선을 통해 품질 혁신 달성.'}
}

# 8. Career Hiring Process (ID 60 - 63)
item_trans[60] = {
    'en': {'title': 'Administrative Screening', 'subtitle': 'Stage 01', 'content': 'Review of qualifications, competencies, and background in line with position requirements.'},
    'ja': {'title': '書類選考・資格確認', 'subtitle': 'ステップ 01', 'content': '応募書類をもとに、募集要件との整合性、専門スキル、これまでの経歴を総合的に確認。'},
    'zh': {'title': '履历审核与资格初筛', 'subtitle': '第一环节 01', 'content': '严格审核应聘者教育经历、专业技能资质及工作履历与岗位需求的契合度。'},
    'ko': {'title': '서류 전형 및 자격 검토', 'subtitle': '1단계 01', 'content': '지원자의 학력, 전문 자격 및 직무 경력이 해당 포지션 요구 조건에 부합하는지 종합 검토.'}
}
item_trans[61] = {
    'en': {'title': 'Technical & Psychometric Test', 'subtitle': 'Stage 02', 'content': 'Evaluation of manufacturing aptitude, technical competencies, and workplace reasoning.'},
    'ja': {'title': '技術適性検査・適性テスト', 'subtitle': 'ステップ 02', 'content': '製造現場での基本適性、論理的思考力、職種に応じた専門スキルの習熟度を評価。'},
    'zh': {'title': '专业能力与综合心理测试', 'subtitle': '第二环节 02', 'content': '全面考核汽车制造基础知识、专业技能熟练度及职场逻辑推理与心理综合素质。'},
    'ko': {'title': '직무 적성 및 기술 평가', 'subtitle': '2단계 02', 'content': '제조 현장 기본 직무 역량, 논리적 사고력 및 전문 직무 기술 숙련도 평가.'}
}
item_trans[62] = {
    'en': {'title': 'Interviews with HR & User Divisions', 'subtitle': 'Stage 03', 'content': 'In-depth discussion on professional vision, corporate culture alignment, and operational readiness.'},
    'ja': {'title': '人事・配属先部門面接', 'subtitle': 'ステップ 03', 'content': '企業文化への適性、業務への熱意、具体的な配属先での役割について面接を実施。'},
    'zh': {'title': '人力资源与用人部门综合面试', 'subtitle': '第三环节 03', 'content': '深入探讨职业愿景、企业文化契合度以及胜任岗位工作所需的实战解决能力。'},
    'ko': {'title': '인사 및 현업 부서 면접', 'subtitle': '3단계 03', 'content': '기업 문화 적합성, 직무 전문성 및 업무 수행 준비도에 대한 심층 면접 진행.'}
}
item_trans[63] = {
    'en': {'title': 'Medical Checkup & Onboarding', 'subtitle': 'Stage 04', 'content': 'Comprehensive health check and onboarding training covering 5S, safety, and Monozukuri basics.'},
    'ja': {'title': '健康診断（MCU）・入社研修', 'subtitle': 'ステップ 04', 'content': '労働安全衛生法に基づく健康診断の実施、および5S・安全教育・モノづくり基本研修。'},
    'zh': {'title': '职业健康体检及入职培训', 'subtitle': '第四环节 04', 'content': '合规进行入职体检，并接受涵盖5S素养、安全规程及日本造物精益基础的入职培训。'},
    'ko': {'title': '신체검사 및 신규 입사자 교육', 'subtitle': '4단계 04', 'content': '법정 건강검진 실시 후 5S, 산업 안전 및 모노즈쿠리 기본기를 다지는 오리엔테이션 교육 진행.'}
}

# 9. Career Culture Benefits (ID 54 - 59)
item_trans[54] = {
    'en': {'title': 'Comprehensive BPJS Health & Employment', 'subtitle': 'Full Insurance Coverage', 'content': 'Full mandatory social security coverage for all permanent employees and dependents.'},
    'ja': {'title': '社会保険（BPJS）完備', 'subtitle': '各種保険完全適用', 'content': '法定の医療保険・雇用保険に完全加入し、従業員と家族の安心を保障。'},
    'zh': {'title': '全面医疗与工伤社会保险（BPJS）', 'subtitle': '全额法定保障', 'content': '依法为全体正式员工及其家属提供完善的国家医疗与劳动社会保障。'},
    'ko': {'title': '사회보험(BPJS) 전액 보장', 'subtitle': '완벽한 보험 혜택', 'content': '모든 임직원과 가족을 위한 법정 의료 및 고용 보험 전액 완비.'}
}
item_trans[55] = {
    'en': {'title': 'Direct Monozukuri Technical Training', 'subtitle': 'Skill Development', 'content': 'Structured technical development under Japanese master technicians and certified trainers.'},
    'ja': {'title': '日本のモノづくり直接技術指導', 'subtitle': '専門技術研修', 'content': '日本人技術者や社内認定トレーナーによる体系的な技術・技能の習得プログラム。'},
    'zh': {'title': '日本母厂精益工艺实操培训', 'subtitle': '专业技能进阶', 'content': '由日本资深工程师及认证讲师进行系统的汽车精密零部件专业制造工艺培训。'},
    'ko': {'title': '일본 본사 모노즈쿠리 기술 전수', 'subtitle': '전문 직무 교육', 'content': '일본인 수석 엔지니어 및 사내 전문 강사의 체계적인 정밀 제조 기술 교육 프로그램 제공.'}
}
item_trans[56] = {
    'en': {'title': 'Hygienic Employee Cafeteria', 'subtitle': 'Daily Nutritious Meals', 'content': 'Complimentary nutritious lunch prepared in certified hygienic catering facilities.'},
    'ja': {'title': '清潔で栄養バランスの良い社員食堂', 'subtitle': '無料の昼食支給', 'content': '衛生管理を徹底した社員食堂で、栄養バランスに優れた温かい昼食を毎日提供。'},
    'zh': {'title': '卫生规范的现代化员工餐厅', 'subtitle': '免费营养午餐', 'content': '在经过严格食品卫生认证的员工餐厅内，每日免费提供营养均衡的午餐配餐。'},
    'ko': {'title': '위생적인 사내 직원 식당', 'subtitle': '영양식 매일 무료 제공', 'content': '위생 인증을 획득한 사내 식당에서 균형 잡힌 따뜻한 무료 영양 점심 식사 제공.'}
}
item_trans[57] = {
    'en': {'title': 'Employee Shuttle Bus Routes', 'subtitle': 'Comfortable Commute', 'content': 'Fleet of air-conditioned shuttle buses covering major residential clusters in Karawang and Purwakarta.'},
    'ja': {'title': '通勤シャトルバス網の運行', 'subtitle': '安心・安全な通勤', 'content': 'カラワン・プルワカルタ主要居住エリアを結ぶ冷房完備の無料通勤送迎バスを運行。'},
    'zh': {'title': '覆盖主要区域的员工通勤班车', 'subtitle': '舒适便捷出行', 'content': '配备覆盖卡拉旺及普哇加达各大居住聚集区的多条空调通勤班车路线。'},
    'ko': {'title': '통근 셔틀버스 노선 운행', 'subtitle': '쾌적하고 안전한 출퇴근', 'content': '카라왕 및 푸르와카르타 주요 주거 지역을 연결하는 에어컨 완비 통근 셔틀버스 무료 운행.'}
}
item_trans[58] = {
    'en': {'title': 'Religious & Sports Facilities', 'subtitle': 'Work-Life Harmony', 'content': 'Spacious prayer room (Musala) and company sports courts fostering camaraderie.'},
    'ja': {'title': '礼拝堂・スポーツ施設の完備', 'subtitle': 'ワークライフ調和', 'content': '広々とした清潔な礼拝室（ムショラ）および社内スポーツ施設を完備。'},
    'zh': {'title': '宗教礼拜室与文体活动场地', 'subtitle': '工作与生活平衡', 'content': '厂区内设有宽敞清洁的穆斯林礼拜室以及各类员工体育活动场地。'},
    'ko': {'title': '사내 기도실 및 체육 복지 시설', 'subtitle': '워라밸 및 건강 증진', 'content': '넓고 쾌적한 사내 기도실(무숄라) 및 임직원 건강 증진을 위한 체육 시설 완비.'}
}
item_trans[59] = {
    'en': {'title': 'Merit-Based Career Progression', 'subtitle': 'Equal Opportunity', 'content': 'Transparent appraisal framework supporting internal promotions and international technical assignments.'},
    'ja': {'title': '公正な能力主義キャリアパス', 'subtitle': '成長と昇進の機会', 'content': '透明性のある人事評価制度により、社内昇格や日本本社への海外研修・派遣の機会を提供。'},
    'zh': {'title': '以绩效为导向的晋升双通道', 'subtitle': '公平发展机遇', 'content': '透明规范的绩效考核体系，大力支持内部优秀人才晋升及前往日本母厂技术研修。'},
    'ko': {'title': '공정한 성과 기반 커리어 패스', 'subtitle': '승진 및 역량 발전 기회', 'content': '투명한 성과 평가 체계를 바탕으로 사내 승진 및 일본 본사 기술 연수 기회 제공.'}
}

# Write complete PHP seed script
with open(r'C:\Devaron\www\cms-compro\scripts\seed_sections_translations.php', 'w', encoding='utf-8') as out:
    out.write('<?php\n')
    out.write('/**\n')
    out.write(' * PT Nihon Plast Indonesia - Complete Database Translation Seeder\n')
    out.write(' * Dynamically writes ALL translations for site_sections and site_section_items into content_translations.\n')
    out.write(' * 100% Free & Open-Source, zero paid external APIs.\n')
    out.write(' */\n\n')
    out.write('require_once __DIR__ . "/../config/database.php";\n')
    out.write('require_once __DIR__ . "/../includes/translation_helpers.php";\n\n')
    out.write('$totalSaved = 0;\n\n')
    out.write('function save_db_trans($type, $id, $lang, $field, $val) {\n')
    out.write('    global $totalSaved;\n')
    out.write('    $val = trim((string)$val);\n')
    out.write('    if ($val === "") return;\n')
    out.write('    if (save_content_translation($type, (int)$id, $lang, $field, $val)) {\n')
    out.write('        $totalSaved++;\n')
    out.write('    }\n')
    out.write('}\n\n')
    out.write('echo "=== SEEDING TRANSLATIONS FOR ALL 44 SITE_SECTIONS ===" . PHP_EOL;\n')

    # Seed site_sections
    for s in secs:
        s_id = s['id']
        s_key = s['section_key']
        if s_key in sec_trans:
            for l_code, fields in sec_trans[s_key].items():
                for f_name, f_val in fields.items():
                    if f_val:
                        escaped = f_val.replace('\\', '\\\\').replace('"', '\\"').replace('$', '\\$')
                        out.write(f'save_db_trans("site_section", {s_id}, "{l_code}", "{f_name}", "{escaped}");\n')

    out.write('\necho "=== SEEDING TRANSLATIONS FOR ALL SITE_SECTION_ITEMS ===" . PHP_EOL;\n')

    # Seed site_section_items
    for item_id, l_data in item_trans.items():
        for l_code, fields in l_data.items():
            for f_name, f_val in fields.items():
                if f_val:
                    escaped = f_val.replace('\\', '\\\\').replace('"', '\\"').replace('$', '\\$')
                    out.write(f'save_db_trans("site_section_item", {item_id}, "{l_code}", "{f_name}", "{escaped}");\n')

    out.write('\necho ">>> FINISHED! Total translations saved: $totalSaved <<<" . PHP_EOL;\n')

print("Generated scripts/seed_sections_translations.php successfully!")
