from collections import Counter

# Mapping for card values (hand type A)
value_mapping_a = {'2': 2, '3': 3, '4': 4, '5': 5, '6': 6, '7': 7, '8': 8, '9': 9, 'T': 10, 'J': 12, 'Q': 13, 'K': 14, 'A': 15}

# Custom sort function for hand type A
def sort_hand_type_a(hand):
    return ''.join(map(str, sorted(list(Counter(hand).values()), reverse=True))), tuple(value_mapping_a[x] for x in hand)

# Main function for hand type A
def calculate_score_type_a():
    score = 0
    hands = []
    with open('7.txt', 'r') as f:
        line = f.readline()
        while line:
            cards, bid = line.split()
            hands.append((cards, int(bid)))
            line = f.readline()
    hands.sort(key=lambda h: sort_hand_type_a(h[0]))
    for idx, (_, bid) in enumerate(hands):
        score += (idx + 1) * bid

    return score

# Mapping for card values (hand type B)
value_mapping_b = {'J': 1, '2': 2, '3': 3, '4': 4, '5': 5, '6': 6, '7': 7, '8': 8, '9': 9, 'T': 10, 'Q': 13, 'K': 14, 'A': 15}

# Custom sort function for hand type B
def sort_hand_type_b(hand):
    counter = Counter(hand)
    if 'J' in counter:
        jokers = counter.pop('J')
        if counter:
            highest = max(counter, key=counter.get)
            counter[highest] += jokers
        else:
            counter['J'] = jokers

    return ''.join(map(str, sorted(list(counter.values()), reverse=True))), tuple(value_mapping_b[x] for x in hand)

# Main function for hand type B
def calculate_score_type_b():
    score = 0
    hands = []
    with open('7.txt', 'r') as f:
        line = f.readline()
        while line:
            cards, bid = line.split()
            hands.append((cards, int(bid)))
            line = f.readline()
    hands.sort(key=lambda h: sort_hand_type_b(h[0]))
    for idx, (_, bid) in enumerate(hands):
        score += (idx + 1) * bid

    return score

if __name__ == '__main__':
    print("Score for Hand Type A:", calculate_score_type_a())
    print("Score for Hand Type B:", calculate_score_type_b())
