import sys

# Using a more descriptive variable name for the list
cumulative_sums = [1] * 200

for i, line in enumerate(sys.stdin):
    # Extracting cards from the input line
    cards = line.strip().split(": ")[1].split(" | ")

    # Creating sets for player's and opponent's cards
    player_cards = set(cards[0].split(" ")) - {''}
    opponent_cards = set(cards[1].split(" ")) - {''}

    # Calculating the number of common cards
    points = len(player_cards.intersection(opponent_cards))

    # Updating the cumulative sums using the defined formula
    for j in range(points):
        cumulative_sums[j + i + 1] += cumulative_sums[i]

# Summing up the cumulative sums for the final result
result = sum(cumulative_sums)

print(result)
