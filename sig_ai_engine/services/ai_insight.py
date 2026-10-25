# services/ai_insight.py

from openai import OpenAI

client = OpenAI(api_key="YOUR_KEY")

def generate_insight(data, type_analysis):
    prompt = f"""
    Kamu adalah analis SIG kampus.

    Jenis analisis: {type_analysis}

    Data:
    {data}

    Berikan:
    1. pola utama
    2. penjelasan
    3. rekomendasi kampus
    """

    response = client.chat.completions.create(
        model="gpt-4o-mini",
        messages=[{"role": "user", "content": prompt}]
    )

    return response.choices[0].message.content