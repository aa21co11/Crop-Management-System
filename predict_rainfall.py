import sys
import random

def predict(region, month):
    """Simple prediction model - replace with your actual ML model"""
    region_base = {
        'North': 80, 'South': 120, 'East': 100, 
        'West': 70, 'Central': 90
    }
    month_multiplier = {
        1: 0.6, 2: 0.7, 3: 0.8, 4: 0.9, 5: 1.2, 
        6: 1.5, 7: 1.8, 8: 1.7, 9: 1.3, 10: 1.0, 
        11: 0.8, 12: 0.7
    }
    base = region_base.get(region, 100)
    multiplier = month_multiplier.get(int(month), 1.0)
    return base * multiplier * (0.9 + random.random() * 0.2)

if __name__ == "__main__":
    try:
        region = sys.argv[1]
        month = int(sys.argv[2])
        rainfall = round(predict(region, month), 1)
        print(rainfall)
    except Exception as e:
        print(f"Error: {str(e)}", file=sys.stderr)
        sys.exit(1)