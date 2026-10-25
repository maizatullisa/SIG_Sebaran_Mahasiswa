from fastapi import FastAPI
from models.schemas import (
    TrendRequest,
    AnomalyRequest,
    DistributionRequest
)

from services.trend import analyze_trend
from services.anomaly import detect_anomaly
from services.distribution import map_distribution

app = FastAPI()

@app.get("/")
def home():
    return {
        "status": "ok",
        "message": "SIG AI Engine berjalan"
    }

@app.post("/trend")
def trend(payload: TrendRequest):
    try:
        result = analyze_trend(payload.data)

        return {
            "status": "success",
            "data": result
        }

    except Exception as e:
        return {
            "status": "error",
            "message": str(e)
        }
    
    
@app.post("/anomaly")
def anomaly(payload: AnomalyRequest):
    return detect_anomaly(payload.data)

@app.post("/distribution")
def distribution(payload: DistributionRequest):
    return map_distribution(payload.data)