from collections import Counter, defaultdict


def detect_anomaly(data):

    if not data:
        return {
            "anomalies": []
        }

    wilayah_data = defaultdict(list)

    for d in data:

        meta = d.meta or {}

        kabupaten = meta.get("kabupaten")

        if not kabupaten:
            continue

        wilayah_data[kabupaten].append(meta)

    hasil = []

    for kabupaten, records in wilayah_data.items():

        angkatan_counter = Counter()

        prodi_counter = Counter()
        minat_counter = Counter()
        jurusan_counter = Counter()
        sekolah_counter = Counter()

        for r in records:

            angkatan = str(r.get("angkatan", ""))

            if angkatan:
                angkatan_counter[angkatan] += 1

            if r.get("prodi"):
                prodi_counter[r["prodi"]] += 1

            if r.get("minat"):
                minat_counter[r["minat"]] += 1

            if r.get("jurusan"):
                jurusan_counter[r["jurusan"]] += 1

            if r.get("nama_sekolah"):
                sekolah_counter[r["nama_sekolah"]] += 1

        tahun = sorted(angkatan_counter.keys())

        if len(tahun) < 2:
            continue

        tahun_lalu = tahun[-2]
        tahun_sekarang = tahun[-1]

        jumlah_lalu = angkatan_counter[tahun_lalu]
        jumlah_sekarang = angkatan_counter[tahun_sekarang]

        if jumlah_lalu == 0:
            continue

        growth = (
            (jumlah_sekarang - jumlah_lalu)
            / jumlah_lalu
        ) * 100

        hasil.append({
            "kabupaten": kabupaten,
            "growth": round(growth, 2),
            "tahun_lalu": tahun_lalu,
            "jumlah_lalu": jumlah_lalu,
            "tahun_sekarang": tahun_sekarang,
            "jumlah_sekarang": jumlah_sekarang,
            "prodi_dominan":
                prodi_counter.most_common(1)[0][0]
                if prodi_counter else None,
            "minat_dominan":
                minat_counter.most_common(1)[0][0]
                if minat_counter else None,
            "jurusan_dominan":
                jurusan_counter.most_common(1)[0][0]
                if jurusan_counter else None,
            "sekolah_dominan":
                sekolah_counter.most_common(1)[0][0]
                if sekolah_counter else None
        })

    hasil = sorted(
        hasil,
        key=lambda x: abs(x["growth"]),
        reverse=True
    )

    return {
        "anomalies": hasil[:10]
    }