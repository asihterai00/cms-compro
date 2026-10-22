# -*- coding: utf-8 -*-
import os

dicts = {
    'id': {
        'topbar_cert': 'IATF 16949 • ISO 14001 • ISO 45001 CERTIFIED',
        'topbar_location': 'Kawasan Industri Indotaisei, Karawang, Jawa Barat',
        'matrix_machines_lab': 'Matriks Mesin & Lab',
        'integrated_plant_title': 'Pabrik Otomotif Terintegrasi di Kawasan Industri Cikampek',
        'integrated_plant_desc': 'Didukung laboratorium inspeksi 3D CMM Mitutoyo, deployment chamber uji ledak airbag, Xenon Arc UV weatherometer, serta jalur painting robotic otomatis untuk menjamin standar kualitas Tier-1 OEM.',
        'office_plant_title': 'Kantor & Fasilitas Pabrik',
        'office_plant_sub': 'Kawasan Industri Indotaisei, Cikampek, Karawang',
        'office_plant_address': 'Alamat Pabrik:',
        'office_plant_phone': 'Telepon:',
        'office_plant_email': 'Email Resmi:',
        'corporate_vision_title': 'Visi Korporat',
        'core_values_title': 'Nilai Inti',
        'footer_tagline': 'Automotive Safety & Precision Monozukuri • Member of Nihon Plast Group (Japan)',
        'prod_steering': 'Steering Wheel Systems (Sistem Roda Kemudi)',
        'prod_airbag': 'Airbag Safety Modules (Modul Kantung Udara)',
        'prod_interior': 'Cockpit & Interior Resins (Resin Interior)',
        'prod_exterior': 'Exterior & Aero Trims (Trim Eksterior)'
    },
    'en': {
        'topbar_cert': 'IATF 16949 • ISO 14001 • ISO 45001 CERTIFIED',
        'topbar_location': 'Indotaisei Industrial Estate, Karawang, West Java',
        'matrix_machines_lab': 'Machinery Matrix & Labs',
        'integrated_plant_title': 'Integrated Automotive Plant at Cikampek Industrial Corridor',
        'integrated_plant_desc': 'Equipped with Mitutoyo 3D CMM metrology, high-speed airbag deployment test chambers, Xenon Arc UV weatherometer, and automated robotic painting cells to ensure Tier-1 OEM standards.',
        'office_plant_title': 'Headquarters & Manufacturing Plant',
        'office_plant_sub': 'Indotaisei Industrial Estate, Cikampek, Karawang',
        'office_plant_address': 'Plant Address:',
        'office_plant_phone': 'Phone:',
        'office_plant_email': 'Official Email:',
        'corporate_vision_title': 'Corporate Vision',
        'core_values_title': 'Core Values',
        'footer_tagline': 'Automotive Safety & Precision Monozukuri • Member of Nihon Plast Group (Japan)',
        'prod_steering': 'Steering Wheel Systems',
        'prod_airbag': 'Airbag Safety Modules',
        'prod_interior': 'Cockpit & Interior Resins',
        'prod_exterior': 'Exterior & Aero Trims'
    },
    'ja': {
        'topbar_cert': 'IATF 16949 • ISO 14001 • ISO 45001 認証取得',
        'topbar_location': '西ジャワ州カラワン県チカンペック インドタイセイ工業団地',
        'matrix_machines_lab': '保有設備マトリクス・試験室',
        'integrated_plant_title': 'チカンペック工業回廊における総合自動車部品製造拠点',
        'integrated_plant_desc': 'ミツトヨ製3D三次元測定機、エアバッグ高速展開試験チャンバー、キセノン耐候試験機、自動ロボット塗装ラインを備え、Tier-1 OEM基準の品質を保証。',
        'office_plant_title': '本社および生産工場拠点',
        'office_plant_sub': '西ジャワ州カラワン県チカンペック インドタイセイ工業団地',
        'office_plant_address': '工場住所:',
        'office_plant_phone': '代表電話:',
        'office_plant_email': '公式メール:',
        'corporate_vision_title': '企業ビジョン',
        'core_values_title': 'コアバリュー（企業価値）',
        'footer_tagline': '自動車安全・精密モノづくり • 日本プラスト株式会社グループ（日本）',
        'prod_steering': 'ステアリングホイールシステム',
        'prod_airbag': 'エアバッグ安全モジュール',
        'prod_interior': 'コックピット＆内装樹脂部品',
        'prod_exterior': 'エクステリア＆エアロトリム'
    },
    'zh': {
        'topbar_cert': 'IATF 16949 • ISO 14001 • ISO 45001 权威认证',
        'topbar_location': '西爪哇省卡拉旺县芝坎佩印多泰成工业区',
        'matrix_machines_lab': '设备规格矩阵与实验室',
        'integrated_plant_title': '位于芝坎佩现代工业廊道的一体化汽车零部件智造基地',
        'integrated_plant_desc': '配备三丰3D三坐标检测仪、安全气囊毫秒级点火展开测试舱、氙灯耐气候试验机及自动化机器人涂装线，全面满足主机厂品质。',
        'office_plant_title': '公司总部与核心生产基地',
        'office_plant_sub': '西爪哇省卡拉旺县芝坎佩印多泰成工业区',
        'office_plant_address': '厂区地址:',
        'office_plant_phone': '电话:',
        'office_plant_email': '官方邮箱:',
        'corporate_vision_title': '企业愿景',
        'core_values_title': '核心价值观',
        'footer_tagline': '汽车安全与高精工匠造物 • 日本精塑株式会社集团成员（日本）',
        'prod_steering': '多功能方向盘安全集成系统',
        'prod_airbag': '乘员保护安全气囊模块',
        'prod_interior': '座舱与中控内饰树脂件',
        'prod_exterior': '外观饰件与空气动力学组件'
    },
    'ko': {
        'topbar_cert': 'IATF 16949 • ISO 14001 • ISO 45001 인증 획득',
        'topbar_location': '서부 자바주 카라왕 치캄펙 인도타이세이 산업단지',
        'matrix_machines_lab': '설비 제원 매트릭스 및 연구소',
        'integrated_plant_title': '치캄펙 산업 복합 단지 내 통합 자동차 부품 제조 공장',
        'integrated_plant_desc': '미쓰토요 3D CMM 측정기, 에어백 고속 전개 시험 챔버, 제논 내후성 시험기 및 자동 로봇 도장 라인을 완비하여 글로벌 Tier-1 품질을 보증합니다.',
        'office_plant_title': '본사 및 제조 공장 시설',
        'office_plant_sub': '서부 자바주 카라왕 치캄펙 인도타이세이 산업단지',
        'office_plant_address': '공장 주소:',
        'office_plant_phone': '대표 전화:',
        'office_plant_email': '공식 이메일:',
        'corporate_vision_title': '기업 비전',
        'core_values_title': '핵심 가치',
        'footer_tagline': '자동차 안전 및 정밀 모노즈쿠리 • 일본 플라스트 그룹 계열사 (일본)',
        'prod_steering': '스티어링 휠 안전 시스템',
        'prod_airbag': '탑승자 보호 에어백 모듈',
        'prod_interior': '콕핏 및 고급 내장재 수지 부품',
        'prod_exterior': '외장 트림 및 에어로 파츠'
    }
}

base_dir = r'C:\Devaron\www\cms-compro\languages'

for lang_code, keys in dicts.items():
    file_path = os.path.join(base_dir, f'{lang_code}.php')
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()

    # Find where the array closes
    idx = content.rfind('];')
    if idx == -1:
        print(f"Error: could not find closing array in {file_path}")
        continue

    # Prepare lines to insert
    insert_lines = "\n    // Extended Topbar, Footer & Plant Elements\n"
    for k, v in keys.items():
        # check if key already exists
        if f"'{k}'" in content:
            continue
        v_escaped = v.replace("'", "\\'")
        insert_lines += f"    '{k}' => '{v_escaped}',\n"

    new_content = content[:idx] + insert_lines + content[idx:]
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(new_content)
    print(f"Updated dictionary: {lang_code}.php")

print("All dictionaries updated successfully!")
