from collections import defaultdict

def map_distribution(data):

    result = defaultdict(int)

    for d in data:

        if hasattr(d, "model_dump"):
            d = d.model_dump()

        district = d.get("district", "Unknown")
        count = int(d.get("count", 0))

        result[district] += count

    sorted_result = sorted(
        result.items(),
        key=lambda x: x[1],
        reverse=True
    )

    total_all = sum(result.values())

    distribution = []

    for district, total in sorted_result:

        percentage = (
            total / total_all * 100
            if total_all > 0
            else 0
        )

        distribution.append({
            "district": district,
            "total": total,
            "percentage": round(percentage, 2)
        })

    top3 = distribution[:3]

    concentration = (
        sum(x["percentage"] for x in top3)
        if top3
        else 0
    )

    spread_score = round(
        100 - concentration,
        2
    )

    return {
        "distribution": distribution,
        "total_mahasiswa": total_all,

        "top_district":
            distribution[0]["district"]
            if distribution
            else None,

        "top_percentage":
            distribution[0]["percentage"]
            if distribution
            else 0,

        "concentration_score":
            round(concentration, 2),

        "spread_score":
            spread_score,

        "distribution_type":
            "terkonsentrasi"
            if concentration > 60
            else "cukup merata"
            if concentration > 40
            else "sangat merata"
    }