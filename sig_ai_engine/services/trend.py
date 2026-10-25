from collections import defaultdict
import numpy as np


def analyze_trend(data):

    if not data:
        return {
            "trend": []
        }

    # ==============================
    # GROUP DATA BY PRODI & ANGKATAN
    # ==============================
    by_prodi = defaultdict(lambda: defaultdict(int))

    for d in data:

        # DataPoint -> dict
        d = d.model_dump()

        meta = d.get("meta") or {}

        angkatan = meta.get("angkatan")
        prodi = meta.get("prodi")

        if not angkatan or not prodi:
            continue

        angkatan = str(angkatan)

        by_prodi[prodi][angkatan] += 1

    results = []

    # ==============================
    # ANALISIS PER PRODI
    # ==============================
    for prodi, years in by_prodi.items():

        years_sorted = sorted(years.keys())
        counts = [years[y] for y in years_sorted]

        if len(counts) < 2:
            continue

        # ==============================
        # BASIC TREND
        # ==============================
        growth = counts[-1] - counts[0]

        growth_percent = (
            (growth / counts[0]) * 100
            if counts[0] > 0
            else 0
        )

        if growth > 0:
            status = "naik"
        elif growth < 0:
            status = "turun"
        else:
            status = "stabil"

        # ==============================
        # MOMENTUM
        # ==============================
        momentum = (
            float(np.mean(np.diff(counts)))
            if len(counts) > 1
            else 0
        )

        # ==============================
        # VOLATILITY
        # ==============================
        volatility = (
            float(np.std(counts))
            if len(counts) > 1
            else 0
        )

        stability_label = (
            "stabil"
            if volatility < 5
            else "fluktuatif"
        )

        # ==============================
        # ACCELERATION
        # ==============================
        acceleration = (
            float(np.mean(np.diff(np.diff(counts))))
            if len(counts) > 2
            else 0
        )

        # ==============================
        # FORECAST
        # ==============================
        next_year = int(
            max(
                0,
                counts[-1] + momentum
            )
        )

        forecast_3_years = [
            int(max(0, next_year + momentum * i))
            for i in range(1, 4)
        ]

        # ==============================
        # HEALTH SCORE
        # ==============================
        health_score = max(
            0,
            min(
                100,
                int(100 - volatility * 2)
            )
        )

        # ==============================
        # CATEGORY
        # ==============================
        if growth_percent > 50:
            category = "explosive growth"

        elif growth_percent > 10:
            category = "steady growth"

        elif growth_percent < -20:
            category = "declining"

        elif volatility > 10:
            category = "unstable"

        else:
            category = "plateau"

        # ==============================
        # INSIGHT
        # ==============================
        insight = []

        if growth_percent > 30:
            insight.append(
                "minat mahasiswa meningkat signifikan"
            )

        elif growth_percent < -30:
            insight.append(
                "terjadi penurunan minat cukup tajam"
            )

        if volatility > 10:
            insight.append(
                "data fluktuatif, perlu investigasi lebih lanjut"
            )

        if momentum > 0:
            insight.append(
                "tren masih menunjukkan pertumbuhan positif"
            )
        else:
            insight.append(
                "tren mulai melambat"
            )

        results.append({
            "prodi": prodi,
            "years": years_sorted,
            "counts": counts,

            "status": status,
            "growth_percent": round(
                growth_percent,
                2
            ),

            "momentum": round(
                momentum,
                2
            ),

            "volatility": round(
                volatility,
                2
            ),

            "acceleration": round(
                acceleration,
                2
            ),

            "prediction_next_year": next_year,

            "forecast_3_years": forecast_3_years,

            "trend_category": category,

            "stability": stability_label,

            "health_score": health_score,

            "insight": insight
        })

    return {
        "trend": results
    }