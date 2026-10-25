from pydantic import BaseModel
from typing import List, Dict, Any, Optional


# ===============================
# 🔹 Generic Data (UNTUK SEMUA ENDPOINT)
# ===============================
class DataPoint(BaseModel):

    # Generic
    value: Optional[float] = None
    label: Optional[str] = None

    # Distribution
    district: Optional[str] = None
    count: Optional[int] = None

    # GIS
    lat: Optional[float] = None
    lng: Optional[float] = None

    # Extra
    meta: Optional[Dict[str, Any]] = None


class GenericData(BaseModel):
    data: List[DataPoint]


# ===============================
# 🔹 TREND ANALYSIS
# contoh: jumlah mahasiswa per bulan
# ===============================
class TrendRequest(BaseModel):
    data: List[DataPoint]
    group_by: Optional[str] = "time"


# ===============================
# 🔹 ANOMALY DETECTION
# contoh: data lokasi / nilai tidak normal
# ===============================
class AnomalyRequest(BaseModel):
    data: List[DataPoint]
    threshold: Optional[float] = 1.5


# ===============================
# 🔹 DISTRIBUTION MAP
# contoh: sebaran mahasiswa di peta
# ===============================
class DistributionRequest(BaseModel):
    data: List[DataPoint]
    zoom_level: Optional[int] = 10