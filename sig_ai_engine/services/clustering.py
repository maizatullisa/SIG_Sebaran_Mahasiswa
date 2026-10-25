from sklearn.cluster import KMeans
import numpy as np

def cluster_regions(data):
    """
    data:
    [
        {"lat": -7.7, "lng": 111.3, "count": 120},
        {"lat": -7.8, "lng": 111.4, "count": 50}
    ]
    """

    X = np.array([[d["lat"], d["lng"], d["count"]] for d in data])

    kmeans = KMeans(n_clusters=3, random_state=42)
    labels = kmeans.fit_predict(X)

    result = []

    for i, d in enumerate(data):
        result.append({
            **d,
            "cluster": int(labels[i])
        })

    return {
        "clusters": result
    }